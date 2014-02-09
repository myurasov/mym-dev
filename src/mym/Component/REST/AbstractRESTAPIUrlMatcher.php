<?php

namespace mym\Component\REST;

use Symfony\Component\HttpFoundation\Request;

use mym\Routing\AbstractUrlMatcher;
use mym\Exception\HttpMethodNotAllowedException;
use mym\Exception\HttpNotFoundException;

abstract class AbstractRESTAPIUrlMatcher extends AbstractUrlMatcher
{
  protected $controllersNamespace = '';

  public function match(Request &$request)
  {
    // path with removed trailing slash
    $path = preg_replace('#^/api/(v1)/#', '', $request->getPathInfo());

    // collections
    if (preg_match('#^
        ([\w]+)(?:\.json)?
        (?:/([\w\-:]+))?
        (?:/([\w\-:]+))?
        (?:/([\w\-:]+))?
        (\.action)?/?
      $#x', $path, $m)) {

      $isAction = isset($m[5]); // is it an action?

      // set accept json header for proper http response format
      $request->headers->set('Accept', 'application/json');

      // id
      if (isset($m[2])) {
        $request->query->set('id', $m[2]);
      }

      // subCollection
      if (isset($m[3]) && !empty($m[3])) {
        $request->query->set('subCollection', $m[3]);
      }

      // subId
      if (isset($m[4]) && !empty($m[4])) {
        $request->query->set('subId', $m[4]);
      }

      $this->controller = [$this->controllersNamespace . '\\' . ucfirst($m[1]) . 'Controller'];
      $this->chooseMethod($request, $isAction);
    }
  }

  /**
   * @throws HttpMethodNotAllowedException
   */
  private function chooseMethod(Request $request, $isAction)
  {
    // choose action
    // http://en.wikipedia.org/wiki/Representational_state_transfer#RESTful_web_APIss

    if ($isAction) { // action

      if ($request->query->has('subCollection')) { // (POST /resources/<id>/action.do)

        $request->query->set('action', $request->query->get('subCollection'));
        $request->query->remove('subCollection');

      } else if ($request->query->has('id')) { // (POST /resources/action.do)

        $request->query->set('action', $request->query->get('id'));
        $request->query->remove('id');

      } else {
        throw new HttpNotFoundException();
      }

      $this->controller[1] =  $request->query->get('action') . 'Action';

    } else if ($request->query->has('subCollection')) { // subCollection

      if ($request->query->has('subId')) { // subResource

        switch ($request->getMethod()) {
          case 'GET':
            $this->controller[1] = 'getSubResourceAction';
            break;
          case 'PUT':
            $this->controller[1] = 'updateOrCreateSubResourceAction'; // or create if not exists
            break;
          case 'DELETE':
            $this->controller[1] = 'deleteSubResourceAction';
            break;
          default:
            $e = new HttpMethodNotAllowedException();
            $e->setAllowedMethods('GET, PUT, DELETE');
            throw $e;
        }

      } else {

        switch ($request->getMethod()) {
          case 'GET':
            $this->controller[1] = 'getSubCollectionAction';
            break;
          case 'PUT':
            $this->controller[1] = 'replaceSubCollectionAction';
            break;
          case 'POST':
            $this->controller[1] = 'createSubResourceAction'; // create new sub-resource
            break;
          case 'DELETE':
            $this->controller[1] = 'deleteSubCollectionAction';
            break;
          default:
            $e = new HttpMethodNotAllowedException();
            $e->setAllowedMethods('GET, PUT, POST, DELETE');
            throw $e;
        }

      }

    } else { // resource

      if ($request->query->has('id') && !is_array($request->query->get('id'))) {

        switch ($request->getMethod()) {
          case 'GET':
            $this->controller[1] = 'getResourceAction';
            break;
          case 'PUT':
            $this->controller[1] = 'updateOrCreateResourceAction'; // or create if not exists
            break;
          case 'DELETE':
            $this->controller[1] = 'deleteResourceAction';
            break;
          default:
            $e = new HttpMethodNotAllowedException();
            $e->setAllowedMethods('GET, PUT, DELETE');
            throw $e;
        }

      } else {

        switch ($request->getMethod()) {
          case 'GET':
            $this->controller[1] = 'getCollectionAction';
            break;
          case 'PUT':
            $this->controller[1] = 'replaceCollectionAction';
            break;
          case 'POST':
            $this->controller[1] = 'createResourceAction'; // create new resource
            break;
          case 'DELETE':
            $this->controller[1] = 'deleteCollectionAction';
            break;
          default:
            $e = new HttpMethodNotAllowedException();
            $e->setAllowedMethods('GET, PUT, POST, DELETE');
            throw $e;
        }

      }

    }
  }
}