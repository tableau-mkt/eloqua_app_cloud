<?php

namespace Drupal\eloqua_app_cloud\Plugin;

/**
 * Base class for Eloqua AppCloud Content Responder plugins.
 *
 */
abstract class EloquaAppCloudContentResponderBase extends EloquaAppCloudInteractiveResponderBase {
  // Add common methods and abstract methods for your plugin type here.

  /**
   * {@inheritdoc}
   */
  public function height() {
    // Retrieve the @height property from the annotation and return it.
    return (string) $this->pluginDefinition['height'];
  }
  /**
   * {@inheritdoc}
   */
  public function width() {
    // Retrieve the @width property from the annotation and return it.
    return (string) $this->pluginDefinition['width'];
  }
  /**
   * {@inheritdoc}
   */
  public function editorImageUrl() {
    // Retrieve the @editorImageUrl property from the annotation and return it.
    return (string) $this->pluginDefinition['editorImageUrl'];
  }
}
