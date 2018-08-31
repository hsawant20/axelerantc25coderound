<?php

namespace Drupal\siteapi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\node\Entity\Node;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * PageNodeJSONController controller.
 */
class PageNodeJSONController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  private $config_factory;

  /**
   * @var \Symfony\Component\Serializer\SerializerInterface $serializer
   */
  private $serializer;

  /**
   * PageNodeJSONController constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *  Config factory service.
   *
   * @param \Symfony\Component\Serializer\SerializerInterface $serializer
   *  Serializer service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, SerializerInterface $serializer) {
    $this->config_factory = $config_factory;
    $this->serializer = $serializer;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // PageNodeJSONController Object creation.
    return new static(
      // Load ConfigFactory service from Container.
      $container->get('config.factory'),
      // Load Serializer service from Container.
      $container->get('serializer')
    );
  }

  /**
   * Function returns JSON representation of a given node with
   *  the content type "page" only if the previously submitted API Key
   *  and a node id (nid) of an appropriate node are present,
   *  otherwise it will respond with "access denied".
   *
   * @param string $siteapikey
   *  Site api key.
   *
   * @param mixed $node
   *  Node data.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
   *  This exception returns system 403 page.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *  Return Json formatted Response.
   */
  public function getPageJSON(string $siteapikey, $node) {
    $site_api_key = $this->config_factory->get('siteapi.details')->get('siteapikey');
    if ($site_api_key === $siteapikey && $node instanceof Node) {
      $data = $this->serializer->serialize($node, 'json');
      return new JsonResponse(json_decode($data));
	}
    // This exception returns system 403 page.
	throw new AccessDeniedHttpException();
  }

  /**
   * Function returns 'Access Denied' Response.
   *
   * @return array
   *  Returns markup for system 403 page.
   */
  public function getPageAccessDenied() {
    return [
      '#markup' => $this->t('Invalid URL or Node Id.'),
    ];
  }

}
