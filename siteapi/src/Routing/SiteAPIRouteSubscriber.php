<?php

namespace Drupal\siteapi\Routing;

use Symfony\Component\Routing\RouteCollection;
use Drupal\Core\Routing\RouteSubscriberBase;

/**
 * Dynamic-route events listener.
 */
class SiteAPIRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // Changed default system-403 controller to PageNodeJSONController.
    if ($route = $collection->get('system.403')) {
      $route->addDefaults(['_controller' => '\Drupal\siteapi\Controller\PageNodeJSONController::getPageAccessDenied']);
    }
  }

}
