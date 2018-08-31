<?php

namespace Drupal\siteapi\Routing;

use Drupal\node\Entity\Node;
use Drupal\Core\ParamConverter\ParamConverterInterface;
use Symfony\Component\Routing\Route;

/**
 * Paramconverter for SiteAPI module.
 */
class SiteAPIParamConverter implements ParamConverterInterface {

  /**
   * {@inheritdoc}
   */
  public function convert($value, $definition, $name, array $defaults) {
    $node = Node::load($value);
	if (!empty($node) && $node->bundle() === 'page') {
      return $node;
	}
    return $value;
  }

  /**
   * {@inheritdoc}
   */
  public function applies($definition, $name, Route $route) {
    // Applies to parameter of type 'page_type'.
    return (!empty($definition['type']) && $definition['type'] == 'page_type');
  }

}
