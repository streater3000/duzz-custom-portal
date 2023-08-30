<?php

namespace Duzz\Shared\Layout\Factory;

use Duzz\Shared\Layout\HTML\Duzz_Return_HTML;
use Duzz\Utils\Duzz_Keys;
use Duzz\Core\Duzz_Helpers;
use Duzz\Core\Duzz_Get_Data;
use Duzz\Shared\Actions\Duzz_Validate_ID;

class Duzz_ProjectPages_Factory {
    private $container;
    private $topUpdates;
    private $flexProgress;
    private $yourProjectStatus;
    private $welcomeBack;
    private $showProgressStatusStage;
    private $progressBarShortcode;
    private $bottomUpdates;
    private $feedTitle;

    public function __construct() {
        $this->container = new Duzz_Return_HTML('div', ['class' => 'container']);
        $this->topUpdates = new Duzz_Return_HTML('div', ['class' => 'top-updates']);
        $this->container->addChild('div', ['class' => 'top-updates']);
    }

    public function addFlexProgress() {
        $this->flexProgress = new Duzz_Return_HTML('div', ['class' => 'flex-progress']);
        $this->flexProgress->addChild('div', [], $this->yourProjectStatus->render());
        $this->flexProgress->addChild('div', [], $this->welcomeBack->render());
        $this->topUpdates->addChild('div', ['class' => 'flex-progress'], $this->flexProgress->render());
    }

    public function addShowProgressStatusStage() {
        if (!isset($_GET['project_id'])) {
            return;
        }

        $project_id = absint(sanitize_text_field($_GET['project_id']));

        if (!Duzz_Validate_ID::validate($project_id)) {
            return 'Invalid project_id provided';
        }
        
        $project_status = Duzz_Helpers::duzz_get_field('project_status', $project_id) ?: '1: Discuss Project';

        $addStatusStage = new Duzz_Return_HTML('div', ['class' => 'show-progress-status-stage flex-stage']);
        $showProgressStatusStage = new Duzz_Return_HTML('span', [], 'Stage&nbsp;' . $project_status);
        $addStatusStage->addChild('span', [], 'Stage&nbsp;' . $project_status);

        return $addStatusStage;
    }

    public function addProgressBar() {
        $combineBar = new Duzz_Return_HTML('div', ['class' => 'progress-bar-creator']);
        $topBar = new Duzz_Return_HTML('div', ['class' => 'progress-bar-shortcode']);

        $project_id = absint($_GET['project_id'] ?? false);

        if ($project_id) {
            $project_status = Duzz_Helpers::duzz_get_field('project_status', $project_id) ?: '1: Stage One';
            $number = explode(':', $project_status);
            $field_object = Duzz_Helpers::duzz_get_field_object('project_status', $project_id);

            if (isset($field_object['choices']) && is_array($field_object['choices'])) {
                $total_steps = count($field_object['choices']);
                $percentage = ($total_steps > 0) ? ($number[0] * (100 / $total_steps)) : 0;
                $width = ($percentage == 100) ? 'calc(100% - 6px)' : $percentage . '%';

                $topBar->addChild('span', ['class' => 'percentage', 'style' => 'width:' . $width]);
            } else {
                $topBar->addChild('span', ['class' => 'acf-error-message'], "ERROR: ACF Field 'project_status' missing.");
            }
        }

        $combineBar->addChild('div', ['class' => 'progress-bar-shortcode-container'], $topBar->render());
        return $combineBar;
    }
}

