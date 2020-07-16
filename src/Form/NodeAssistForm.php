<?php
/**
 * @file
 * Contains \Drupal\node_assist\Form\NodeAssistForm
 */
namespace Drupal\node_assist\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

class NodeAssistForm extends Formbase {
    public function getFormId() {
        return 'node_assist_form';
      }

    public function buildForm(array $form, FormStateInterface $form_state){
        $node_types = \Drupal\node\Entity\NodeType::loadMultiple();
        $options = [];
        foreach ($node_types as $node_type) {
            $options[$node_type->id()] = $node_type->label();
        }
        
        $form['no_of_nodes'] = [
            '#type' => 'number',
            '#title' => $this->t('Number of Nodes'),
          ];

          $form['content_type'] = array(
            '#title' => t('Content Type'),
            '#type' => 'select',
            '#options' => $options,
          );

          $form['node_title'] = [  
            '#type' => 'textarea',  
            '#title' => $this->t('Title'),  
          ];
          
          $form['node_body'] = [  
            '#type' => 'textarea',  
            '#title' => $this->t('Body'),  
          ];
              
          $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
          ];
      
          return $form;
    }    

    public function validateForm(array &$form, FormStateInterface $form_state) {
        $value = $form_state->getValue('no_of_nodes');
        if ($value >5) {
          $form_state->setErrorByName('no_of_nodes',t('Number of nodes cannot be more than 5.'));
        }
          return;
        }

    public function submitForm(array &$form, FormStateInterface $form_state) {
            for ($i=0; $i < $form_state->getValue('no_of_nodes'); $i++) { 
                $node = Node::create(array(
                    'type' => $form_state->getValue('content_type'),
                    'title' => $form_state->getValue('node_title'),
                    'body' => $form_state->getValue('node_body'),
                    'langcode' => 'en',
                    'uid' => '1',
                    'status' => 1,
                    'field_fields' => array(),
                ));
                $node->save();
            }
            
            drupal_set_message(t('Nodes created sucessfully'));    
    }
}

