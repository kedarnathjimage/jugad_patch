<?php

namespace Drupal\jugaad_patch\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use chillerlan\QRCode\{QRCode, QROptions};
use Drupal\Component\Render\FormattableMarkup;

/**
 * Provides a custom Product Block.
 * @Block(
 *   id = "product_custom_block",
 *   admin_label = @Translation("Scan here on your mobile")
 * )
 */
class ProductBlock extends BlockBase {

    /**
     * {@inheritdoc}
     */
     public function build() {
        $node = \Drupal::routeMatch()->getParameter('node');
        $current_path = \Drupal::service('path.current')->getPath();
        $nid = $node ? $node->id() : 0;
        $node_details = Node::load($nid);
        $app_link = $node_details ? $node_details->get('field_app_purchase_link')->getValue()[0]['uri'] : '';        
        $img = (new QRCode)->render($app_link);
        $form['app_url'] = [
          '#type' => 'markup',
          '#markup' => new FormattableMarkup('<p>To purchase this product on our app to avail to avail exclusive app-only<br><img src="data::src" alt="QR Code"></img></p>', [':src' => $img])
        ];

        $form['#cache'] = ['max-age' => 0];

        return $form;
     }

}