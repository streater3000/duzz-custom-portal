<?php 

namespace Duzz\Shared\Layout\Pages;

use Duzz\Shared\Layout\HTML as HTMLNamespace;
use Duzz\Shared\Actions as ActionsNamespace;
use Duzz\Shared\Layout\Factory as FactoryNamespace;
use Duzz\Shared\Actions\Duzz_Popup;

class Duzz_Projects_Page_Content extends Duzz_Project_Constructor {

    private static $instance = null;
    private $field_data;

    public static function duzz_get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->field_data = get_option('duzz_settings_project_page_field_data', array());
        parent::__construct();
    }

    public function duzz_addTopUpdates() {
        parent::duzz_addTopUpdates();
        $this->topUpdates->duzz_addChild('', [], $this->duzz_addAddressInfo());
        $this->topUpdates->duzz_addChild('', [], $this->duzz_addAccountInfo());
    }


    public function duzz_addCenterUpdates() {
        parent::duzz_addCenterUpdates();

            $projectUpdateInstance = new ActionsNamespace\Duzz_Project_Update();
            $projectUpdateOutput = $projectUpdateInstance->duzz_project_update_output();

        $ProjectUpdate = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'project-update-margin-top'));
        $ProjectUpdate->duzz_addChild('', [], $projectUpdateOutput);

        $this->topRow->duzz_addChild('', [], $ProjectUpdate); 
        $this->centerUpdates->duzz_addChild('', [], $this->duzz_addProjectTabs());
    }

    public function duzz_addBottomUpdates() {
        parent::duzz_addBottomUpdates();
    }

    public function duzz_addAddressInfo() {
        $topSection = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'side-margins-customer-view'));
        $AddressRow = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'account-first-row account-relative'));

        $AddressRow1 = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'column-account'));
        $selected_fields = isset($this->field_data['main_data']) ? $this->field_data['main_data'] : '';
        $labeled_data = new FactoryNamespace\Duzz_Labeled_Data($selected_fields);
        $AddressRow1->duzz_addChild('', [], $labeled_data->duzz_render_all_fields());

        $popup = new Duzz_Popup();
        $popup_content = $popup->duzz_manage_project_popup();

        $AddressRow2 = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'column-account'));
        $AddressRow2->duzz_addChild('', [], $popup_content);

        $AddressRow->duzz_addChild('', [], $AddressRow1);
        $AddressRow->duzz_addChild('', [], $AddressRow2);

        $topSection->duzz_addChild('', [], $AddressRow);

        return $topSection;

    }

    public function duzz_addAccountInfo() {
        $topSection = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'side-margins-customer-view'));
        $accountFirstRow = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'account-first-row account-relative'));
        $selected_fields = isset($this->field_data['header_data']) ? $this->field_data['header_data'] : '';
        $labeled_data = new FactoryNamespace\Duzz_Labeled_Data($selected_fields);
        $accountFirstRow->duzz_addChild('', [], $labeled_data->duzz_render_all_fields());

        $accountSecondRow = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'account-second-row account-relative'));

        $topSection->duzz_addChild('', [], $accountFirstRow);
        $topSection->duzz_addChild('', [], $accountSecondRow);

        return $topSection;
    }

    public function duzz_addProjectTabs() {
        $projectTabs = new FactoryNamespace\Duzz_ProjectTabs();


        $info_selected_fields = isset($this->field_data['info_tab_fields']) ? $this->field_data['info_tab_fields'] : '';
        $update_selected_fields = isset($this->field_data['updates_tab_fields']) ? $this->field_data['updates_tab_fields'] : '';

        $sendPayment = new FactoryNamespace\Duzz_Send_Payment();

        $info_fields = new FactoryNamespace\Duzz_Fields_Factory($info_selected_fields);
        $update_fields = new FactoryNamespace\Duzz_Fields_Factory($update_selected_fields);
        $paymentTableForm = $sendPayment->duzz_get_payment_table();

        $projectTabs->duzz_addTabContent(array(
            'label' => 'Info',
            'content' => $info_fields->duzz_render_all_fields(),
        ), [], 'tab_info');
        $projectTabs->duzz_addTabContent(array(
            'label' => 'Update',
            'content' => $update_fields->duzz_render_all_fields(),
        ), [], 'tab_update');
        $projectTabs->duzz_addTabContent(array(
            'label' => 'Funds',
            'content' => $paymentTableForm->duzz_render(),
        ), [], 'tab_funds');

        do_action('add_project_tabs', $projectTabs);

        $this->centerUpdates->duzz_addChild('', [], $projectTabs->duzz_render());
    }

}
