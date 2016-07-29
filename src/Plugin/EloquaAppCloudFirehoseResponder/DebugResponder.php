<?php

namespace Drupal\eloqua_app_cloud\Plugin\EloquaAppCloudFirehoseResponder;

use Drupal\eloqua_app_cloud\Plugin\EloquaAppCloudFirehoseResponderBase;

/**
 * Class DebugResponder
 * @package Drupal\eloqua_app_cloud\Plugin\EloquaAppCloudFirehoseResponder
 */
class DebugResponder extends EloquaAppCloudFirehoseResponderBase {

  /**
   * @var LoggerInterface
   */
  protected $logger;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ClientFactory $eloqua, LoggerInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $eloqua);
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('eloqua.client_factory'),
      $container->get('logger.channel.eloqua_app_cloud')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function execute(array &$render, array $params) {
    $this->logger->debug('Received firehose service hook with params !params', [
      '!params' => '<pre>' . print_r($params, TRUE) . '</pre>',
    ]);
  }

}