<?php 

namespace Duzz\Shared\Layout\Pages;

use Duzz\Shared\Layout\HTML as HTMLNamespace;
use Duzz\Shared\Actions as ActionsNamespace;
use Duzz\Shared\Layout\Factory as FactoryNamespace;
use Duzz\Shared\Actions\Duzz_Popup;

class Duzz_Projects_Page_Content extends Duzz_Project_Constructor {

    private static $instance = null;
    private $field_data;

    public static function get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        $this->field_data = get_option('settings_project_page_field_data', array());
        parent::__construct();
    }

    public function addTopUpdates() {
        parent::addTopUpdates();
        $this->topUpdates->addChild('', [], $this->addAddressInfo());
        $this->topUpdates->addChild('', [], $this->addAccountInfo());
    }


    public function addCenterUpdates() {
        parent::addCenterUpdates();

            $projectUpdateInstance = new ActionsNamespace\Duzz_Project_Update();
            $projectUpdateOutput = $projectUpdateInstance->project_update_output();

        $ProjectUpdate = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'project-update-margin-top'));
        $ProjectUpdate->addChild('', [], $projectUpdateOutput);

        $this->topRow->addChild('', [], $ProjectUpdate); 
        $this->centerUpdates->addChild('', [], $this->addProjectTabs());
    }

    public function addBottomUpdates() {
        parent::addBottomUpdates();
    }

    public function addAddressInfo() {
        $topSection = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'side-margins-customer-view'));
        $AddressRow = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'account-first-row account-relative'));

        $AddressRow1 = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'column-account'));
        $selected_fields = isset($this->field_data['main_data']) ? $this->field_data['main_data'] : '';
        $labeled_data = new FactoryNamespace\Duzz_Labeled_Data($selected_fields);
        $AddressRow1->addChild('', [], $labeled_data->render_all_fields());

        $popup = new Duzz_Popup();
        $popup_content = $popup->manage_project_popup();

        $AddressRow2 = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'column-account'));
        $AddressRow2->addChild('', [], $popup_content);

        $AddressRow->addChild('', [], $AddressRow1);
        $AddressRow->addChild('', [], $AddressRow2);

        $topSection->addChild('', [], $AddressRow);

        return $topSection;

    }

    public function addAccountInfo() {
        $topSection = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'side-margins-customer-view'));
        $accountFirstRow = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'account-first-row account-relative'));
        $selected_fields = isset($this->field_data['header_data']) ? $this->field_data['header_data'] : '';
        $labeled_data = new FactoryNamespace\Duzz_Labeled_Data($selected_fields);
        $accountFirstRow->addChild('', [], $labeled_data->render_all_fields());

        $accountSecondRow = new HTMLNamespace\Duzz_Return_HTML('div', array('class' => 'account-second-row account-relative'));

        $topSection->addChild('', [], $accountFirstRow);
        $topSection->addChild('', [], $accountSecondRow);

        return $topSection;
    }

    public function addProjectTabs() {
        $projectTabs = new FactoryNamespace\Duzz_ProjectTabs();

        $info_selected_fields = isset($this->field_data['info_tab_fields']) ? $this->field_data['info_tab_fields'] : '';
        $update_selected_fields = isset($this->field_data['updates_tab_fields']) ? $this->field_data['updates_tab_fields'] : '';

        $sendPayment = new FactoryNamespace\Duzz_Send_Payment();

        $info_fields = new FactoryNamespace\Duzz_Fields_Factory($info_selected_fields);
        $update_fields = new FactoryNamespace\Duzz_Fields_Factory($update_selected_fields);
        $paymentTableForm = $sendPayment->get_payment_table();

        $projectTabs->addTabContent(array(
            'label' => 'Info',
            'content' => $info_fields->render_all_fields(),
        ), [], 'tab_info');
        $projectTabs->addTabContent(array(
            'label' => 'Update',
            'content' => $update_fields->render_all_fields(),
        ), [], 'tab_update');
        $projectTabs->addTabContent(array(
            'label' => 'Funds',
            'content' => $paymentTableForm->render(),
        ), [], 'tab_funds');

        do_action('add_project_tabs', $projectTabs);

        $this->centerUpdates->addChild('', [], $projectTabs->render());
    }

}
