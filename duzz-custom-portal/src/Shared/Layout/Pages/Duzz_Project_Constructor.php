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

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->container = new Duzz_Return_HTML('div', array('class' => 'staff-pages-container'));
        $this->projectPages = new FactoryNamespace\Duzz_ProjectPages_Factory(); 
        $this->addErrorMessage = new Duzz_Error();
        $this->addMessage();
        $this->addTopUpdates();
        $this->addCenterUpdates();
        $this->addBottomUpdates();
    }

public function addMessage() {
    $this->addMessage = new Duzz_Return_HTML('div', array('class' => 'top-updates side-margins-account project-page-error-message'));
    $messageDiv = new Duzz_Return_HTML('div', array('class' => 'message-error-page'));
    
    // Correctly get the error messages using the static method
    $errorMessage = Duzz_Error::list_error_messages();

    $messageDiv->addChild('', [], $errorMessage);
    $this->addMessage->addChild($messageDiv);
    $this->container->addChild($this->addMessage);
}

    public function addTopUpdates() {
        $this->topUpdates = new Duzz_Return_HTML('div', array('class' => 'top-updates side-margins-account'));
        $this->topSection = new Duzz_Return_HTML('div', array('class' => 'customer-info-container'));
        $this->topUpdates->addChild($this->topSection);
        $this->container->addChild($this->topUpdates);
    }

    public function addCenterUpdates() {
        $this->centerUpdates = new Duzz_Return_HTML('div', array('class' => 'center-updates side-margins-account margin-bottom-account'));
        
        $this->topRow = new Duzz_Return_HTML('div', array('class' => 'flex-stage'));
        $progressDiv = new Duzz_Return_HTML('div', array('class' => 'progress-stage'));
        
        $progressStage = new Duzz_Return_HTML('div', array('class' => 'progress-title'));
        $progressStage->addChild('', [], 'Project Progress');
        $progressDiv->addChild($progressStage);
        
        if (function_exists('acf_form_head')) {
            $progressDiv->addChild($this->projectPages->addShowProgressStatusStage());
            $this->topRow->addChild($progressDiv);
            $this->centerUpdates->addChild($this->topRow);
            $this->centerUpdates->addChild($this->projectPages->addProgressBar());
        }
        
        $this->container->addChild($this->centerUpdates);
    }

    public function addBottomUpdates() {
        $this->bottomUpdates = new Duzz_Return_HTML('div', array('class' => 'bottom-updates side-margins-account margin-bottom-account'));
        
        $comment_title_container = new Duzz_Return_HTML('div', array('class' => 'feed-title'));
        $comment_title_container->addChild('', [], 'status feed');
        $this->bottomUpdates->addChild($comment_title_container);
        
        // Instantiate Comment_Factory and get comments HTML
        $comment_factory = new Duzz_List_Factory("comments_list", "project", "comments", "paged", 5);
        $comments_html = $comment_factory->create_list();
        $this->bottomUpdates->addChild('', [], $comments_html);
        
        $this->container->addChild($this->bottomUpdates);
    }
}
