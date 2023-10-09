<?php

namespace Duzz\Shared\Layout\Pages;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;
use Duzz\Shared\Layout\Factory as FactoryNamespace;
use Duzz\Shared\Actions\Duzz_Error;

class Duzz_Project_Constructor {
    public $container;
    public $topUpdates;
    public $bottomUpdates;
    public $projectPages;

    public static function duzz_get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->container = new Duzz_Return_HTML('div', array('class' => 'staff-pages-container'));
        $this->projectPages = new FactoryNamespace\Duzz_ProjectPages_Factory(); 
        $this->addErrorMessage = new Duzz_Error();
        $this->duzz_addMessage();
        $this->duzz_addTopUpdates();
        $this->duzz_addCenterUpdates();
        $this->duzz_addBottomUpdates();
    }

public function duzz_addMessage() {
    $this->addMessage = new Duzz_Return_HTML('div', array('class' => 'top-updates side-margins-account project-page-error-message'));
    $messageDiv = new Duzz_Return_HTML('div', array('class' => 'message-error-page'));
    
    // Correctly get the error messages using the static method
    $errorMessage = Duzz_Error::duzz_list_error_messages();

    $messageDiv->duzz_addChild('', [], $errorMessage);
    $this->addMessage->duzz_addChild($messageDiv);
    $this->container->duzz_addChild($this->addMessage);
}

    public function duzz_addTopUpdates() {
        $this->topUpdates = new Duzz_Return_HTML('div', array('class' => 'top-updates side-margins-account'));
        $this->topSection = new Duzz_Return_HTML('div', array('class' => 'customer-info-container'));
        $this->topUpdates->duzz_addChild($this->topSection);
        $this->container->duzz_addChild($this->topUpdates);
    }

    public function duzz_addCenterUpdates() {
        $this->centerUpdates = new Duzz_Return_HTML('div', array('class' => 'center-updates side-margins-account margin-bottom-account'));
        
        $this->topRow = new Duzz_Return_HTML('div', array('class' => 'flex-stage'));
        $progressDiv = new Duzz_Return_HTML('div', array('class' => 'progress-stage'));
        
        $progressStage = new Duzz_Return_HTML('div', array('class' => 'progress-title'));
        $progressStage->duzz_addChild('', [], 'Project Progress');
        $progressDiv->duzz_addChild($progressStage);
        
        if (function_exists('acf_form_head')) {
            $progressDiv->duzz_addChild($this->projectPages->duzz_addShowProgressStatusStage());
            $this->topRow->duzz_addChild($progressDiv);
            $this->centerUpdates->duzz_addChild($this->topRow);
            $this->centerUpdates->duzz_addChild($this->projectPages->duzz_addProgressBar());
        }
        
        $this->container->duzz_addChild($this->centerUpdates);
    }

    public function duzz_addBottomUpdates() {
        $this->bottomUpdates = new Duzz_Return_HTML('div', array('class' => 'bottom-updates side-margins-account margin-bottom-account'));
        
        $comment_title_container = new Duzz_Return_HTML('div', array('class' => 'feed-title'));
        $comment_title_container->duzz_addChild('', [], 'status feed');
        $this->bottomUpdates->duzz_addChild($comment_title_container);
        
        // Instantiate Comment_Factory and get comments HTML
        $comment_factory = new Duzz_List_Factory("comments_list", "project", "comments", 5);
        $comments_html = $comment_factory->duzz_create_list();
        $this->bottomUpdates->duzz_addChild('', [], $comments_html);
        
        $this->container->duzz_addChild($this->bottomUpdates);
    }
}
