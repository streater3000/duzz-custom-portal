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
        $this->container->duzz_addChild('div', ['class' => 'top-updates']);
    }

    public function duzz_addFlexProgress() {
        $this->flexProgress = new Duzz_Return_HTML('div', ['class' => 'flex-progress']);
        $this->flexProgress->duzz_addChild('div', [], $this->yourProjectStatus->duzz_render());
        $this->flexProgress->duzz_addChild('div', [], $this->welcomeBack->duzz_render());
        $this->topUpdates->duzz_addChild('div', ['class' => 'flex-progress'], $this->flexProgress->duzz_render());
    }

public function duzz_addShowProgressStatusStage() {
    $project_id = absint(get_query_var('project_id', false));

    if (!$project_id || !Duzz_Validate_ID::duzz_validate($project_id)) {
        return 'Invalid project_id provided';
    }

    $project_status = Duzz_Helpers::duzz_get_field('project_status', $project_id) ?: '1: Discuss Project';
    
    $addStatusStage = new Duzz_Return_HTML('div', ['class' => 'show-progress-status-stage flex-stage']);
    $addStatusStage->duzz_addChild('span', [], 'Stage&nbsp;' . $project_status);

    return $addStatusStage;
}


public function duzz_addProgressBar() {
    $combineBar = new Duzz_Return_HTML('div', ['class' => 'progress-bar-creator']);
    $topBar = new Duzz_Return_HTML('div', ['class' => 'progress-bar-shortcode']);

    $project_id = absint(get_query_var('project_id', false));

    if ($project_id) {
        $project_status = Duzz_Helpers::duzz_get_field('project_status', $project_id) ?: '1: Stage One';
        $number = explode(':', $project_status);
        $field_object = Duzz_Helpers::duzz_get_field_object('project_status', $project_id);

        if (isset($field_object['choices']) && is_array($field_object['choices'])) {
            $total_steps = count($field_object['choices']);
            $percentage = ($total_steps > 0) ? ($number[0] * (100 / $total_steps)) : 0;
            $width = ($percentage == 100) ? 'calc(100% - 6px)' : $percentage . '%';

            $topBar->duzz_addChild('span', ['class' => 'percentage', 'style' => 'width:' . $width]);
        } else {
            $topBar->duzz_addChild('span', ['class' => 'acf-error-message'], "ERROR: ACF Field 'project_status' missing.");
        }
    }

    $combineBar->duzz_addChild('div', ['class' => 'progress-bar-shortcode-container'], $topBar->duzz_render());
    return $combineBar;
}

}

