<?php

namespace Drupal\eloqua_app_cloud\Plugin;

/**
 * Base class for Eloqua AppCloud Interactive Responder plugins.
 *
 */
abstract class EloquaAppCloudInteractiveResponderBase extends EloquaAppCloudResponderBase implements EloquaAppCloudInteractiveResponderInterface {

  // Add common methods and abstract methods for your plugin type here.

  /**
   * {@inheritdoc}
   */
  public function api() {
    // Retrieve the @api property from the annotation and return it.
    return (string) $this->pluginDefinition['api'];
  }
  /**
   * {@inheritdoc}
   */
  public function fieldList() {
    // Retrieve the @fieldList property from the annotation and return it.
    return (array) $this->pluginDefinition['fieldList'];
  }
  /**
   * {@inheritdoc}
   */
  public function queueWorker() {
    // Retrieve the @queueWorker property from the annotation and return it.
    return (string) $this->pluginDefinition['queueWorker'];
  }

  /**
   * {@inheritdoc}
   */
  public function respond() {
    // Retrieve the @respond property from the annotation and return it.
    return (string) $this->pluginDefinition['respond'];
  }

  /**
   * {@inheritdoc}
   */
  public function description() {
    // Retrieve the @description property from the annotation and return it.
    return (string) $this->pluginDefinition['description'];
  }

}
