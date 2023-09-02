<?php

namespace Duzz\Shared\Layout\CSS;

class Duzz_Class_Factory {

    public function __construct() {
        add_action('admin_head', array($this, 'duzz_admin_dynamic_css'));
                add_action('wp_head', array($this, 'duzz_dynamic_css'));
    }

    public function duzz_admin_dynamic_css() {
    ?>
    <style type="text/css">


/* Hide the default checkbox */
.toggle-switch input[type="checkbox"] {
  position: absolute;
  opacity: 0;
}

/* Customize the toggle switch appearance */
.toggle-switch .slider {
  position: relative;
  display: block;
  width: 50px; /* Adjust the width of the toggle switch */
  height: 26px; /* Adjust the height of the toggle switch */
  border-radius: 13px; /* Make it rounded to create a circular switch */
  background-color: #ccc; /* Background color of the switch */
  cursor: pointer;
}

/* Style the sliding part of the toggle switch */
.toggle-switch .slider:before {
  position: absolute;
  content: "";
  height: 20px; /* Adjust the height of the slider */
  width: 20px; /* Adjust the width of the slider */
  top: 3px; /* Adjust the position of the slider */
  left: 3px; /* Adjust the position of the slider */
  border-radius: 50%; /* Make the slider rounded to create a circular handle */
  background-color: white; /* Color of the slider handle */
  transition: 0.4s; /* Add a smooth transition effect */
}

/* Style the toggle switch when it is checked (on) */
.toggle-switch input[type="checkbox"]:checked + .slider {
  background-color: #5fe38b; /* Background color of the switch when checked */
}

/* Move the slider to the right when the toggle switch is checked (on) */
.toggle-switch input[type="checkbox"]:checked + .slider:before {
  transform: translateX(24px); /* Adjust the distance the slider moves to the right */
}


        .form-table td {
  padding: 5px 0px !important;
}

        .duzz-menu-border-bottom {
            padding: 10px;
        }

        .postbox{
            box-shadow: 0 2px 4px rgba(0,0,0,0.05)!important;
            border-radius: 10px!important;
            border: none!important;
        }


.table-no-border{
    margin-bottom: 5px;
}
        h2 {
  color: #347EFF;
  font-size: 1.3em;
  margin: 1em 0;
}

.form-table th {
  padding: 0px 10px 20px 0;
  }

    .wp-core-ui .button-primary{
    background-color: #5fe38b;
    border: none;
  }

      .wp-core-ui .button-primary:hover{
    background-color: #b9ebc9;
    border: none;
  }

  .notice-info{
    border-left-color: #ff423a;
  }

.author.column-author {
  max-width: 300px;
}

  .select2-container--default .select2-results__option--selected{
                display: none;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice{
                background-color: #5fe38b!important;
                border: 1px solid #5fe38b!important;
                color: #fff!important;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                color: #fff!important;
                outline: none;
                border-right: 1px solid #fff!important;
            }

            .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover, 
            .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:focus {
                background-color: #e4f4ea;
                color: #5fe38b!important;
                outline: none;
            }



.stripe-test-toggle table {
  table-layout: fixed;
    width: 88%;
}


.stripe-test-toggle td {
  width: 300px;
  padding: 8px;
  text-align: left;
}


@media only screen and (min-width:850px) {
            .select2 {
                min-width: 500px;
            }
}

#message {
  width: 100%;
  height: 100px;
}

    </style>
    <?php
    }



function duzz_dynamic_css() {
    ?>
 <style type="text/css">



.featherlight-content p{
    margin-bottom: 10px!important;
}
.featherlight-content form {
  width: 30vw;
  min-width: 500px;
  align-self: center;
  border-radius: 7px;
}

.featherlight-content .hidden {
  display: none;
}

.featherlight-content #payment-message {
  color: rgb(105, 115, 134);
  font-size: 16px;
  line-height: 20px;
  padding-top: 12px;
  text-align: center;
}

.featherlight-content #payment-element {
  margin-bottom: 24px;
}

.stripe-popup-container button {
  background: #5469d4;
  font-family: Arial, sans-serif;
  color: #ffffff;
  border-radius: 4px;
  border: 0;
  padding: 12px 16px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  display: block;
  transition: all 0.2s ease;
  width: 100%;
}
.stripe-popup-container button:hover {
  filter: contrast(115%);
      background: #8b9bee;
}
.stripe-popup-container button:disabled {
  opacity: 0.5;
  cursor: default;
}

/* spinner/processing state, errors */
.featherlight-content .spinner,
.featherlight-content .spinner:before,
.featherlight-content .spinner:after {
  border-radius: 50%;
}
.featherlight-content .spinner {
  color: #ffffff;
  font-size: 22px;
  text-indent: -99999px;
  margin: 0px auto;
  position: relative;
  width: 20px;
  height: 20px;
  box-shadow: inset 0 0 0 2px;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);
}
.featherlight-content .spinner:before,
.featherlight-content .spinner:after {
  position: absolute;
  content: "";
}
.featherlight-content .spinner:before {
  width: 10.4px;
  height: 20.4px;
  background: #5469d4;
  border-radius: 20.4px 0 0 20.4px;
  top: -0.2px;
  left: -0.2px;
  -webkit-transform-origin: 10.4px 10.2px;
  transform-origin: 10.4px 10.2px;
  -webkit-animation: loading 2s infinite ease 1.5s;
  animation: loading 2s infinite ease 1.5s;
}
.featherlight-content .spinner:after {
  width: 10.4px;
  height: 10.2px;
  background: #5469d4;
  border-radius: 0 10.2px 10.2px 0;
  top: -0.1px;
  left: 10.2px;
  -webkit-transform-origin: 0px 10.2px;
  transform-origin: 0px 10.2px;
  -webkit-animation: loading 2s infinite ease;
  animation: loading 2s infinite ease;
}

@-webkit-keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes loading {
  0% {
    -webkit-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

@media only screen and (max-width: 600px) {
  .featherlight-content form {
    width: 80vw;
    min-width: initial;
  }
}

table{
    border: 0px !important;
}
            .form-table td{
                padding: 0px 0px!important;
                font-size: 17px!important;
            }
            .select2 {
                width: 100% !important;
            }

            .select2-container .select2-selection--multiple{
                min-height: 35px!important;
            }

            .select-title-margin{
                margin-left: 5px;
            }
            .custom-dropdown-wrapper {
                position: relative;
                display: inline-block;
                width: 100%;
            }
                 .custom-dropdown-wrapper::after {
            content: '';
            background: #fff url(data:image/svg+xml;charset=US-ASCII,%3Csvg%20width%3D%2220%22%20height%3D%2220%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Cpath%20d%3D%22M5%206l5%205%205-5%202%201-7%207-7-7%202-1z%22%20fill%3D%22%23555%22%2F%3E%3C%2Fsvg%3E) no-repeat right 4px top 6px;
            position: absolute;
            right: 8px;
            top: 35%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            pointer-events: none;
        }
        
.duzz-input {
    margin-bottom:10px;
}

.flex-email { 
    display: flex; 
    margin-bottom: 5px; 
}

.duzz-label {
    font-weight: bold
}

.reset-field-width{
    width:100%!important;
}

.feed-title{
    color: #FF433A;

    font-size: 30px;
    font-weight: 900;
}

.tribute-container{
    max-width: 100%!important;
    background-color: #e3e3e3!important;
}

.tribute-container li{
    padding: 5px;
    list-style-type: none;
}

.tribute-container li:hover{
    background-color: #5fe38b;
}

.chatfeed-title {
  margin-top: -20px;
}

.reset-field-width {
  margin-bottom: 5px;
}

.fields-column-account{
    width: 90$;
}
.customer-address1-width,
.customer-address2-width{
    width:  100%;
}

.customer-city-width{
    width: 45%;
}

.duzz-custom-field-container{
    margin-bottom: 5px;
}
/* Apply flex properties to the children of address-container */
.address-container .column-account {
  flex: 1;
  min-width: 100px; /* Adjust this value to control the minimum width of the address fields */
}


/* Apply flex-wrap to the address-container */
.address-container {
  display: flex;
  flex-wrap: wrap;
}

.duzz-address-field{
    margin-right:  5px;
}


.acf-fields > .acf-field{
    margin: 0px;
    padding: 0px!important;
    margin-bottom: 10px!important;
}

.acf-fields::after {
  display: table;
  clear: both;
  content: "";
  height: 0em; /* Adjust this value as needed to position the button below the fields */
}


.acf-fields.acf-form-fields.-top {
  position: initial;
}

.acf-updated-message {
  color: red;
  margin-left: 5px;
  margin-bottom: 10px;
  font-weight: 500;
}

.fields-factory-container .min-width-flex{
    margin-bottom: 20px!important;
}

/*GRID MOBILE STACK VIEW*/
@media only screen and (min-width:850px) {
  .label-customer-mobile{
  display:none;
}
}

@media only screen and (max-width:850px) {

.website-tr {
  display: none;
}
.duzz-project-list, .customer-email-tr{
    display: none;
}
.locked-column .label-customer-mobile{
    display: none!important;
}
.locked-column{
    text-align: left!important;
}
.duzz-project-list-row td:last-child{
    order: -1;
  }
  .duzz-project-list-row {
    display: flex!important;
    flex-direction: column!important;
    margin-bottom: 10px;
  }
}
.new_project_title{
    display: none;
}

.pagination-div-two{
    margin-bottom: 50px;
}
p{
    margin-bottom: 5px!important;
}

textarea{
    margin-bottom: 20px!important;
}

.status-feed-form textarea{
    width: 100% !important;
    min-height: 130px;
}


.display-none-project {
  border: 3px solid black !important;
    font-weight: 900;
  text-align: center;
  background-color: #fff!important;
}

.completed-project{
  background-color: #5fe38b !important;
  color: white;
  font-weight: 900;
  text-align: center;
}

.archived-project{
     background-color: #000 !important;
  color: white;
  font-weight: 900;
  text-align: center; 
}


.new-project {
    background-color: #ff423a!important;
    color: white;
    font-weight: 900;
      text-align: center;
}


.flex-stage{
  display:flex;
  margin-bottom: 0px;
  position: relative;
}

.show-progress-status-stage{
  font-weight: 600;
  margin-bottom: 5px;
}


.progress-bar-shortcode {
  width: 100%;
  background:#ffffff;
  margin-bottom:5px;
  border-radius: 20px;
  max-width: 2500px;
  border: 3px solid #ff423a!important;
}
.progress-bar-shortcode .percentage {
    background-color: #ff423a;
    padding: 10px 0;
    margin: 3px;
    text-indent: 15px;
    display: block;
    color: #fff;
    min-height: 35px;
    border-radius: 20px;

}


.featherlight-content textarea {
  margin-top: 10px;
  border: 0px solid white;
  border-radius: 10;
  padding: 10px 15px;
  margin-bottom: 5px;
  width: 100%;
  height: 100px;
}


.staff-page-flex {
    min-width: 100%!important;
}

.container.grid-container {
    width: 100%!important;
}


.page-item-9920,
.page-item-9918,
.page-item-9919,
.page-item-9921,
.page-item-9923,
.page-item-9924,
.page-item-9925,
.page-item-9926,
.page-item-9927,
.page-item-9928,
.page-item-9929,
.page-item-9930{
    display: none;
}


.staff-sidebar > .page-item-9920,
.staff-sidebar > .page-item-9918,
.staff-sidebar > .page-item-9919,
.staff-sidebar > .page-item-9921,
.staff-sidebar > .page-item-9922,
.staff-sidebar > .page-item-9923,
.staff-sidebar > .page-item-9924,
.staff-sidebar > .page-item-9925,
.staff-sidebar > .page-item-9926,
.staff-sidebar > .page-item-9927,
.staff-sidebar > .page-item-9928,
.staff-sidebar > .page-item-9929,
.staff-sidebar > .page-item-9930 {
  display: block !important; 
}


@media screen and (max-width: 750px){
.role_duzz_staff .page-item-9921,
.role_duzz_team_leader .page-item-9921,
.role_duzz_subadmin .page-item-9921,
.role_duzz_admin .page-item-9921,
.role_administrator .page-item-9921,

.role_duzz_staff .page-item-9923,
.role_duzz_team_leader .page-item-9923,
.role_duzz_subadmin .page-item-9923,
.role_duzz_admin .page-item-9923,
.role_administrator .page-item-9923,

.role_duzz_staff .page-item-9926,
.role_duzz_team_leader .page-item-9926,
.role_duzz_subadmin .page-item-9926,
.role_duzz_admin .page-item-9926,
.role_administrator .page-item-9926,


.role_duzz_team_leader .page-item-9928,
.role_duzz_subadmin .page-item-9928,
.role_duzz_admin .page-item-9928,
.role_administrator .page-item-9928,


.role_duzz_subadmin .page-item-9929,
.role_duzz_admin .page-item-9929,
.role_administrator .page-item-9929,


.role_duzz_staff .page-item-9927,
.role_duzz_team_leader .page-item-9927,
.role_duzz_subadmin .page-item-9927,
.role_duzz_admin .page-item-9927,
.role_administrator .page-item-9927{
    display: block!important;
}
}


.updated{
    color: red;
}

h5{


            font-weight: 900;
            margin-bottom: 10px!important;
}
/*Sidebar menu flex*/

@media screen and (max-width: 750px){
.left-staff {
  display: none;
}
}
.staff-page-flex{
    display: flex;
        width: 100%;

}


.left-staff {
    order: -10;
    width: 15%;
    margin-right: 10px;
    min-width: 160px;
}

.right-staff{
    width: 100%!important;
}


.site-content .content-area {
    width: 100% !important;
}


.page-id-9919 .is-right-sidebar,
.page-id-9920 .is-right-sidebar,
.page-id-9921 .is-right-sidebar,
.page-id-9922 .is-right-sidebar,
.page-id-9923 .is-right-sidebar,
.page-id-9924 .is-right-sidebar,
.page-id-9925 .is-right-sidebar,
.page-id-9926 .is-right-sidebar,
.page-id-9927 .is-right-sidebar,
.page-id-9928 .is-right-sidebar,
.page-id-9929 .is-right-sidebar,
.page-id-9930 .is-right-sidebar, {
    display: none;
}

.staff-page-flex ul{
    margin: 0 0 0 0!important;
    position: fixed!important;
}
/*sidebar menu design*/


.add-project-menu {
    margin-bottom: 20px;
    margin-left:  -10px;
}
.add-project-menu a{
outline: none;
    cursor: pointer;
    padding: 10px 20px 10px 20px !important;
    text-align: center;
    transition: 0.3s;
    font-size: 17px!important;
    background-color: #5fe38b;
    border-radius: 20px !important;
    font-weight: 800;

    position: relative !important;
    border: 5px solid white;
    color: white !important;
    text-decoration: none;
    margin-left: 20px;
}

.add-project-menu .current_page_item{
    background-color: #347EFF!important;
}

.add-project-menu a:active{
background-color: #000!important;
}

.add-project-menu a:hover{
    background-color: #9effbe !important;
}


.staff-menu-item a {
  color: #000;
  border-radius: 0 20px 20px 0;
  font-size: 25px;

  font-weight: 500;
  line-height: 0.9;
  min-width: 160px!important;
  display: block;
  margin-bottom: 3px;
  padding-bottom: 3px;
  padding-top: 3px;
    text-decoration: none;
    padding-left: 20px;
}

.staff-menu-item a:hover {
  background-color: #5fe38b8f;
}

.staff-menu-item a {
  text-decoration: none;
}

.staff-sidebar .page_item a {
  background-color: #347EFF;
  color: white!important;
}


.project-table-height {
    padding-top: 20px;
    padding-bottom: 10px;
}
.pagination-div-one{
      background-color: white;
          padding-top: 20px;
      height: 50px;
      width: 100%;
}

.duzz-project-list-header{
  display: none;
}



.pagination-div{
    margin-bottom: 20px;
}
.page-numbers.current, .page-numbers.current:hover {
  background: #347EFF!important;
  padding: 10px;
  font-size: 20px;

  font-weight: 900;
  color: white!important;
  border: 3px solid #347EFF;
  border-radius: 10px;
}

.page-numbers {
  background: white!important;
  padding: 10px!important;
  font-size: 20px;

  font-weight: 900;
  color: #5fe38b!important;
  border: 3px solid #5fe38b;
    border-radius: 10px;
}

.page-numbers:hover {
  background: #5fe38b!important;
  padding: 10px;
  font-size: 20px;

  font-weight: 900;
  color: white!important;
    border-radius: 10px;
}






#menu-sidebar-menu .staff-menu-item[class*="current-menu-"] > a {
  background-color: #347EFF !important;
  color: white !important;
  padding: 3px 3px 3px 20px;
  z-index: 10;
}

#menu-sidebar-menu .menu-item-0000[class*="current-menu-"] > a {
  background-color: #347EFF !important;
  color: white !important;
  padding: 3px !important;
  padding-left: 30px!important;
  margin-left: -27px;
  z-index: 10;
}

.customer-name-table{
    margin-right: 0px!important;
    max-width: 100%!important;
}

@media (min-width: 850px) {

.staff-sidebar{
    list-style-type: none!important;
        max-width: 200px !important;
        position: fixed !important;
}

.page-content-flex-container {
  display: flex;
}

.page-content-flex-container > .sidebar-container {
  flex: 0 0 20%;
  max-width: 20%;
}

.page-content-flex-container > .content {
  flex: 0 0 80%;
  max-width: 80%;
}
}

@media (max-width: 850px) {
    .page-content-flex-container > * {
        flex: 1 1 100%;
}
.sidebar-container{
    margin-bottom: 20px!important;
}
.staff-sidebar{
    list-style-type: none!important;
        max-width: 100%!important;
        position: relative;
}
.customer-name-tr{
    display: none;
}
}

.content-container{
    width:  95%!important;
}


.sidebar-container{
    padding-top: 20px!important;
}


/*Table design*/

.call-list-table td {
  font-size: 10px!important;
}


@media only screen and (max-width:850px) {
.duzz-new-project{
  width: 100%!important;
}
}

@media only screen and (min-width:850px) {
.label-customer-mobile{
    display: none;
}
}

.custom-selected-table tr:hover td {
  background-color: #5fe38b;
}


td:hover {
    cursor: pointer;
}

.duzz-project-list-row, .duzz-staff-list-row, .duzz-team-list-row, .duzz-company-list-row {
    background-color: #eefff4;
}

th {

    color: #347EFF;
    font-size: 15px;
    font-weight: 500;
border-width: 0 0 0 0!important;
}

.invoice-info-wrapper th,
.invoice-info-wrapper td{
    padding: 0 0px 0px 5px !important;
}

.custom-selected-tr th {
    border-width: 0 3px 3px 0;
    border: 3px solid rgb(255, 255, 255);
    padding: 0px !important;
    border-radius: 10px !important;  
}


.custom-selected-tr th.view-mobile-green {
  border: 3px solid rgb(255, 255, 255) !important;
}

.custom-selected-tr th.status-update{
    padding: 2px!important;
}


.duzz-staff-list-row td,
.duzz-project-list-row td, 
.duzz-company-list-row td {
    border-width: 0 3px 3px 0;
    border: 3px solid rgb(255, 255, 255);
    padding: 8px!important;
    border-radius: 10px!important;
}



.duzz-staff-list-row td, 
.duzz-team-list-row td {
    border-width: 0 3px 3px 0;
    border: 3px solid rgb(255, 255, 255);
    padding: 5px!important;
}

.duzz-project-list, .duzz-company-list-row, .duzz-team-list-row, .duzz-staff-list-row{
font-size: 10px;
}

.duzz-project-list, .duzz-staff-list, .duzz-team-list, .duzz-company-list {
    border: 0px solid rgb(0 0 0 / 0%);
}


@media only screen and (max-width:850px) {
.duzz-project-list-header, 
.duzz-company-list-header, 
.duzz-team-list-header, 
.duzz-staff-list-header {
    display: none;
}

.projects-mobile-border-bottom{
    border-bottom: 2px solid white !important;
}

.duzz-project-list-row td, 
.duzz-company-list-row td, 
.duzz-team-list-row td, 
.duzz-staff-list-row td {
  padding: 0px;
  border-radius: 10px!important;
  border: 3px solid rgb(255, 255, 255);
}

.label-customer-mobile{
min-width: 100px !important;
display: inline-flex!important;
  padding: 8px!important;
  margin-right:10px!important;
  border-right:3px white;
  font-weight: 900;
}
.data-customer-mobile{
  width: 50%;
  display: block!important;
  padding: 8px!important;
}
td{
  display: flex;

}
td {
  padding: 0px;
  border-radius: 10px!important;
  border: 3px solid rgb(255, 255, 255);
}

.duzz-project-list-row, .duzz-company-list-row, 
.duzz-team-list-row, .duzz-staff-list-row {
    margin-bottom: 10px !important;
    display: block;
}

.view-mobile-green{
display: block;
}

.view-mobile-white{
  background-color: white;
}
}


/*Fields*/

/* Mobile-first approach */
@media (max-width: 850px) {  /* You can adjust the breakpoint as per your requirements */
    table.custom-selected-table {
        width: 100%;  /* Ensure table is full width */
    }
    table.custom-selected-table th {
        display: block;  /* Make each header cell a block-level element */
        width: 100%;     /* Full width for each cell */
    }
    /* If the parent row uses flex, adjust its direction for mobile */
    table.custom-selected-table tr.custom-selected-tr {
        flex-direction: column;
    }

    .custom-selected-tr input[type="text"]{
    margin-bottom: 5px;
    }
    .submit-button-class{
        margin-bottom: 10px;
    }

    .view-mobile-green{
    display: none;
}
    .page-id-9920 .custom-selected-tr{
        display: none;
    }
}



.editable-selected-fields-container {
  width: 100%;
  height: 100%;
  margin-bottom: 20px !important;
  position: relative;
  display: inline-block;
}

.duzz-basic-fields{
    width: 100%;
}
.acf-fields-width input, 
.acf-fields-width select{
    width: 100%!important;
}

input[type=number],
input[type=password],
input[type=email],
input[type=text], 
input[type=time], 
input[type=url], 
input[type=week], 
input[type=tel],
select, 
textarea {
    height: 40px;
    background-color: #d7fde7;
    border-radius: 10px!important;
    padding: 8px;
    max-width: 95%;
}

input.acf-is-prepended{
    border-radius: 0 10px 10px 0 !important;
}

.acf-input-prepend,
.acf-is-prepended {
    height: 40px; /* adjust as necessary */
}

.acf-input-prepend,
.acf-is-prepended {
    box-sizing: border-box; /* or content-box, as needed */
}


.acf-input-prepend{
    float: left;
    font-size: 13px;
    padding: 10px!important;
    border-radius: 10px 0 0 10px;
    background: #5fe38b!important;
    border-right: #ffffff solid 2px!important;
    min-height: 30px!important;
    border: #fff solid 0px!important;
}



.page-id-9930 select,
.page-id-9930 input[type=text],
.page-id-9928 input[type=number],
.page-id-9928 [type=email],
.page-id-9928 input[type=tel],
.page-id-9928 input[type=text], 
.page-id-9928 select 
{
    width: 18%!important;
}

div.wpforms-container-full .wpforms-form input.wpforms-field-medium, 
div.wpforms-container-full .wpforms-form select.wpforms-field-medium, 
div.wpforms-container-full .wpforms-form .wpforms-field-row.wpforms-field-medium{
        max-width: 100%;
}

div.wpforms-container-full {
    margin: 0!important;
    padding-bottom: 50px;
}
/*Button*/


.wpforms-submit-container button,
.duzz-inline-form input[type=submit],
.form-input-button,
.featherlight-content input[type=submit],
.featherlight-content button[type=submit],
.staff-pages-container input[type=submit], 
.staff-pages-container button[type=submit], 
.staff-pages-container .wpforms-page-button,
input[type=submit] {
    text-decoration: none!important;
    background-color: #5fe38b!important;
    color: #ffffff!important;
    font-size: 1em;
    padding: 10px 20px;
    border-radius: 10px!important;
    font-size: 17px!important;
    font-weight: 800!important;
    min-width: 20%;
    margin-top: 0px;
}

.custom-selected-table input[type=submit]{
    padding: 5px;
}


.total-invoice-pricing-container button{
        text-decoration: none!important;
    background-color: #5fe38b!important;
    color: #ffffff!important;
    font-size: 1em;
    padding: 10px 40px;
    border-radius: 20px!important;
    font-size: 17px!important;
    font-weight: 800!important;
    min-width: 20%;
    margin-top: 0px;
}

.total-invoice-pricing-container button:hover{
    background-color: #9effbe!important;
}


.featherlight-content input[type=submit],
.featherlight-content button[type=submit]{
    width: 100%!important;
}

.featherlight-content select{

            background-color: #beffd3!important;
            width: 100%;
}

.featherlight-content h5{
    margin-top: 20px!important;

            font-weight: 900;
}

@media screen and (max-width: 649px){
    .staff-pages-container input[type=submit], 
.staff-pages-container button[type=submit], 
.staff-pages-container .wpforms-page-button {
    width: 100%;
}
}


.wpforms-submit-container button :hover,
.duzz-inline-form input[type=submit] :hover,
button[type="submit"]:hover,
.form-input-button:hover,
.featherlight-content input[type=submit]:hover,
.featherlight-content button[type=submit]:hover,
.staff-pages-container input[type=submit]:hover, 
.staff-pages-container  button[type=submit]:hover, 
.staff-pages-container .wpforms-page-button:hover,
input[type=submit]:hover {
    background: none!important;
    background-color: #9effbe!important;
}


/*Account page*/
.password-section-size label{
    width: 300px!important;
    display: inline-flex;
}

.password-name-spacing{
    margin-bottom: 5px;
}


/*messages*/


/*featherlight*/

.modal { 
    display: none; 
}

textarea{
    padding: 10px 15px;
}

.featherlight-content input[type="text"]{
    margin-bottom: 10px;
}

.featherlight-content input[type="text"]{
    width: 100%;
}

.featherlight .featherlight-close-icon{
        right: 0;
    line-height: 25px;
    width: 25px;
    cursor: pointer;
    text-align: center;
    font-family: Arial,sans-serif;
    background: #fff;
    background: white;
    color: #5fe38b;
    border: 0;
    margin: 10px;
    font-size: 30px;
    padding-top: 5px;
    padding-right: 35px;
    font-weight: 900;
}

.featherlight .featherlight-content{
    text-align: left;
    vertical-align: middle;
    display: inline-block;
    overflow: auto;
    padding: 40px;
    border-bottom: 25px solid transparent;
    margin-left: 5%;
    margin-right: 5%;
    max-height: 95%;
    background: #fff;
    cursor: auto;
    white-space: normal;
    border-radius: 20px;
}

@media only screen and (max-width: 650px){
.page-id-2878 .featherlight-content select {
    width: 100%;
    padding: 5px;
}
.featherlight .featherlight-content{
    padding-top: 40px;
    padding-left: 10px;
    padding-right: 10px;
}
.page-id-2878 .featherlight-content .blue-titles{
    display: none;
}
}



.edit-button-position{
    position: absolute;
text-align: center !important;
right: 60px;
    text-decoration: none!important;
    background-color: #5fe38b!important;
    color: #ffffff!important;
    font-size: 1em;
    padding: 10px 40px;
    border-radius: 20px!important;
 
    font-size: 17px!important;
    font-weight: 800!important;
    margin-top: 10px;
}

.edit-button-position:hover {
    background: none!important;
    background-color: #9effbe!important;
}

@media only screen and (max-width: 649px){
.edit-button-position{
        padding: 10px 25px;
        margin-right: -30px;
}

.popup-width{
    width: 100%;
}
}

///*Tabs*///

.tabs {
    display: block;
}


/* Style the tab */
.tab {
  overflow: hidden;
  border: 1px solid #fff;
  background-color: #fff;
  max-width: 2500px;
}

/* Style the buttons inside the tab */
.tablinks {
    display: inline-block;
    background-color: inherit;
    width: 31%;
    float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 0px 14px 0px !important;
    text-align: center;
    margin: 3px !important;
    transition: 0.3s;
    font-size: 17px;
    color: #fff;
    background-color: #5fe38b;
    border-radius: 20px!important;
    font-size: 25px !important;
    font-weight: 800;
    position: relative!important;
    border: 5px solid white;
    z-index: 1!important;
}
.tablinks:hover {
    background-color: #9effbe !important;
}
.tab-center{
    margin-left: auto;
    margin-right: auto;
    display: block;
}



@media only screen and (max-width:650px) {
.tablinks {
    font-size: 18px !important;
}

.tab .active {
    font-size: 18px !important;
}
}
/* Change background color of buttons on hover */
.tab button:hover {
  background-color: #5fe38b;
color: #fff
}

/* Create an active/current tablink class */
.tab .active {
 background-color: inherit;
float: left;
    border: none;
    outline: none;
    cursor: pointer;
    padding: 14px 50px;
    margin: 5px;
    transition: 0.3s;
    font-size: 17px;
    border-radius: 20px;
    font-size: 25px;
    font-weight: 800;

   position: relative!important;
      border: 3px solid white;
  background-color: #fff;
color: #5fe38b;
border-radius: 20px;
z-index: 0;
border: 5px solid #5fe38b;
}

.tabs {
    min-width: 100%;
    min-height: 100px;
}

/* Style the tab content */
.tabcontent {
  display: none;
  padding: 6px 12px;
  border: none;
  border-top: none;
  max-width: 2500px;
  min-height: 210px!important;
}

.tabcontent.defaultTabContent {
  display: block;
}

@media only screen and (max-width:650px) {
    .tabcontent {
  margin-right: 0px;
    }
}

.page-id-2878 #Funds .min-width-flex{
    min-width: 0px!important;
    max-width: 250px!important;
    margin-right: 40px!important;
}

.acf-field .acf-label label {
    display: block;
    font-weight: 500;
    margin: 0 0 3px;
    padding: 0;
        color: #347EFF;

}


/*Comments*/


.comment__avatar img {
    /* Your styles here */
    width: 50px; /* Example */
    height: 50px; /* Example */
    border-radius: 50%; /* Example to make it round */
    margin-right: 10px;
}

.comment__info-container {
    display: left;
    align-items: center;
}

.comment__avatar, 
.comment__info-container {
    display: inline-block;

}

.comment__avatar {
  position: relative;
}

.comment__avatar::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;

  z-index: 1;
}

.comment__avatar > img {
  position: relative;
  z-index: 0;
}

/* @ notification stylinh */
.chatfeed-title {
    margin-top: 0px;
}

.comment__author {
    min-width: 110px;
    margin-left: 5px;
}

.new-project,
.comment__author-role,
.mention__author-role {
    min-width: 100px;
    text-align: center;
}

.mention--role_duzz_bot .mention__project-link{
    color: white;
}

    .comment, .mention {
                margin: 1rem 0;
                padding: 10px 1rem .1rem 2rem;
                                border: 3px solid #ff423a;
                border-radius:  0 20px 20px 0;
                background: white;
            }
            .comment__author, .mention__author {
                display: inline-block;
                font-weight: bold;
                color: #f02d2d;
            }
            .comment__author-role, .mention__author-role {
                display: inline-block;
            font-size: 80%;
            background-color: #f02d2d;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            color: white;
            font-weight: bold;
            margin-left: 0rem;
            }
            .comment__date, .mention__date {
                font-size: 75%;
                font-style: italic;
            }
            .comment__content, .mention__content {
                margin-top: 5px;
            }
    .comment__content p{
                margin-bottom: 10px!important;
            }

            #updates {
                margin-top: 0rem;
                margin-bottom: 0rem;
            }
            .submit-project-update-button {
                margin-top: .5rem;
            }
            .comment--role_duzz_bot, .mention--role_duzz_bot {
                border-color: #347EFF;
                background-color: #347EFF;
                border-radius: 20px;
                color: white;
            }

        .comment--role_duzz_bot a{
                color: white!important;
            }

            .comment--role_duzz_bot  .comment__author, .mention--role_duzz_bot .mention__author {
                display: inline-block;
                font-weight: bold;
                color: white!important;
            }

            .comment--role_duzz_bot .comment__author-role, .mention--role_duzz_bot .mention__author-role {
                background-color: white!important;
                color: #347EFF;
            }
            .comment--role_administrator, .mention--role_administrator {
                border: 3px solid #347EFF;
                border-radius: 0 20px 20px 0;
                background-color: white;
            }
            .comment--role_administrator .comment__author-role, .mention--role_administrator .mention__author-role {
                background-color: #347EFF;
            }

            .comment--role_administrator .comment__author, 
            .mention--role_administrator .mention__author{
                color: #347EFF;
            }
            
            .comment--role_duzz_admin , 
            .mention--role_duzz_admin{
                border-color: #347EFF!important;
            }
            .comment--role_duzz_admin .comment__author-role, 
            .mention--role_duzz_admin .mention__author-role {
                background-color: #347EFF;
                border: 3px solid #347EFF!important;
            }

            .comment--role_duzz_admin .comment__author, 
            .mention--role_duzz_admin .mention__author{
                    color:  #347EFF;
            }
            .comment--role_duzz_subadmin, 
            .mention--role_duzz_subadmin {
                border-color: #ffbb32!important;
            }
            .comment--role_duzz_subadmin .comment__author-role, 
            .mention--role_duzz_subadmin .mention__author-role{
                background-color: #ffbb32;
            }
            .comment--role_duzz_subadmin .comment__author, 
            .mention--role_duzz_subadmin .mention__author{
                color: #ffbb32;
            }
            .comment--role_duzz_team_leader, .mention--role_duzz_team_leader  {
                border-color: #ffbb32!important;
            }
            .comment--role_duzz_team_leader .comment__author-role, .mention--role_duzz_team_leader .mention__author-role {
                background-color: #ffbb32;
            }

            .comment--role_duzz_team_leader .comment__author, .mention--role_duzz_team_leader .mention__author{
                color: #ffbb32;
            }
            .comment--role_duzz_staff, .mention--role_duzz_staff {
                border-color: #ffbb32!important;
                            border: 3px solid #ffbb32;
            }
            .comment--role_duzz_staff .comment__author-role, .mention--role_duzz_staff .mention__author-role  {
                background-color: #ffbb32;
            }

                    .comment--role_duzz_staff .comment__author, .mention--role_duzz_staff .mention__author {
                color: #ffbb32;
}

/*edit user*/

.edit-user-controls form::after{
  content: "|";
  margin-right: 5px;
  margin-left: 5px;
}

.edit-user-controls form {
  display: inline-block;
  color: #2369ff;
  font-size: 12px !important;
  font-weight: normal;
  border: 0;
  background: none !important;
  padding: 0;
}

.edit-user-controls input[type="submit"]{
    color: #3242df;
    background-color: white;
}


input.restore-user-link, input.archive-user-link, input.delete-user-link, input.archive-team-link {
  color: #2369ff;
  font-size: 12px !important;
  font-weight: normal;
  border: 0;
  background: none !important;
  padding: 0;
}


.account-titles {
    font-size: 18px;
    font-weight: 800;
    color: #2369ff;
}


.acf-width-flex{
    margin-bottom: 20px;
}

@media only screen and (min-width: 981px){
.page-id-1942 h1.entry-title{
margin-top: 70px!important;
margin-left: 20px!important;
margin-bottom: 10px!important;
}

.page-id-1942 .entry-content:not(:first-child), .page-id-1942 .entry-summary:not(:first-child), .page-id-1942 .page-content:not(:first-child){
  margin-top: 0px!important;
}

.policy-review-titles{
    font-size: 30px;
  font-weight:800;
  color: #347EFF;
  margin-left: 20px;
}

.acf-width-flex{
    margin-bottom: 20px;
}
.min-width-flex{

margin-right: 30px;
margin-bottom:  50px;
}
.min-width-flex-percent{
  min-width:200px!important;
  max-width: 300px!important;
  margin-right: 20px!important;
}
.address-fields-width{
    margin-right: 50px;
}
}

.account-first-row, .account-second-row, .account-third-row, .account-fourth-row, .account-fifth-row{
display: flex;
  flex-wrap: wrap;
  flex-direction: initial;
}


@media (max-width: 980px) and (min-width:865.25px){
  .page-id-1942 h1.entry-title{
    padding-left: 25px;
    margin-bottom: 10px!important;
}
.page-id-1942 .side-margins-account{
      padding-left: 0px!important;
}
.policy-review-titles{
    font-size: 30px;
  font-weight:800;
  color: #347EFF;
  margin-left: 0px;
}
.min-width-flex{
min-width: 200px;
max-width: 300px;
margin-right: 20px;
margin-bottom:  50px;
}

.acf-width-flex{
    margin-bottom: 20px;
}

.min-width-flex-percent{
  min-width:135px!important;
  max-width: 170px!important;
  margin-right: 20px!important;
}
.address-fields-width{
    margin-right: 50px;
}
}

@media (max-width: 865.25px) {

  .page-id-1942 .entry-content:not(:first-child), .page-id-1942 .entry-summary:not(:first-child), .page-id-1942 .page-content:not(:first-child){
  margin-top: 10px!important;
}
  .page-id-1942 h1.entry-title{
  margin-bottom: 20px!important;
              color:#000000;
}
.policy-review-titles{
    font-size: 30px;
  font-weight:800;
  color: #347EFF;
  margin-left: 20px;
}
.page-id-1942 .side-margins-account{
      padding-left: 20px!important;
}



  .financials-shortcodes{
      font-size: 40px!important;
}
.min-width-flex{
min-width: 100px;
margin-right: 20px;
margin-bottom:  50px;
}
.min-width-flex-percent{
  max-width: 150px!important;
  margin-right: 20px!important;
  margin-bottom: 10px!important;
}

.project-update-margin-top {
  position: absolute;
  right: 0px!important;
  }
}

.duzz-success {
  color: #31a275;
  font-weight: 500;
}

.message-error-page{
    margin-bottom: 20px!important;
}



.title-styling {
    color: #FF433A;

    font-weight: 900;
}

.text-styling {

    font-weight: 300;
    color: #FF433A;
}

.background-color {
    color: #FF433A;
}

.status-feed-form-textarea {
    width: 100% !important;
}

.show-progress-status-stage {
    font-weight: 600;
    margin-bottom: 5px;
}

.flex-stage {
    display: flex !important;
    margin-bottom: 0px;
}

.welcome-back {
    margin-left: auto;
    display: flex;
}


.page_numbers {
    text-decoration: none !important;
}

.other_class {
    color: #ff0000;
    font-weight: bold;
    text-decoration: none !important;
}

.prev {
    text-decoration: none !important;
}

.feed-title {
    color: #FF433A;

    font-size: 30px;
    font-weight: 900;
    margin-bottom: 0px;
}

.new-comment {
    position: relative;
}

.new-comment::before {
    content: "New Message";
    font-weight: 900;
    background: #5fe38b;
    color: white;
    padding: 5px 10px;
    border-radius: 3px;
    position: absolute;
    right: 30px;
    top: 10px;
    font-size: 12px;
}

.submit-project-update-button:disabled {
    background-color: #dddddd !important;
    color: grey !important;
}

.progress-bar-shortcode {
    width: 100%;
    background: #ffffff;
    margin-bottom: 5px;
    border-radius: 20px;
    max-width: 2500px;
    border: 3px solid #ff423a !important;
}

.progress-bar-shortcode .percentage {
    background-color: #ff423a;
    padding: 10px 0;
    margin: 3px;
    text-indent: 15px;
    display: block;
    color: #fff;
    min-height: 35px;
    border-radius: 20px;
}


    .top-updates {
    margin-bottom: -10px;
}

.field-project_status {
    margin-bottom: 5px !important;
}

.flex-progress {
    margin-top: -35px;
    margin-bottom: 0px;
    font-size: 25px;
    font-weight: 900;
    color: #347EFF;
    height: 30px;

}

.project-update-margin-top {
  position: absolute;
  right: 60px;
  }

.progress-title {
    margin-top: 0px;
    margin-bottom: 0px;
    font-size: 25px;
    font-weight: 900;
    color: #347EFF;
    height: 30px;

}

.welcome-back-mobile {
    margin-bottom: 0px !important;
}

.show-progress-status-stage {
    font-weight: 600;
    margin-bottom: 5px;
}

.stages-link {
    color: #347EFF;
    cursor: pointer;
}

.stages-link:hover {
    color: #347EFF;
    cursor: pointer;
    text-decoration: none;
    opacity: 0.5;
}

.flex-progress {
    display: flex;
}

.welcome-back {
    margin-left: auto;
    display: flex;
}

.welcome-back-mobile {
    display: none;
    padding-bottom: 0px !important;
    font-size: 25px;
    font-weight: 900;
    color: #347EFF;
    display: flex;
    line-height: 1em !important;

}
.bottom-updates,
.top-updates,
.center-updates{
    max-width: 1100px;
    margin-right: auto;
    margin-left: auto;
}

.margin-bottom-account {
    margin-bottom: 10px !important;
}

.customer-address-title {
    font-size: 25px;
    font-weight: 800;
    color: #347EFF;
}


    .account-relative {
    position: relative;
}

.account-titles {
    font-size: 18px;
    font-weight: 800;
    color: #347EFF;
}

.account-titles-black {
    font-size: 18px;
    font-weight: 800;
    color: #000000;
}

.account-first-row,
.account-second-row,
.account-third-row,
.account-fourth-row,
.account-fifth-row {
    display: flex;
    flex-wrap: wrap;
    flex-direction: initial;
}

.column-account {
    float: left;
}

.right-account {
    padding-left: 0px;
}

.column-right {
    width: 170px;
}

.column-left {
    width: 260px;
    padding-bottom: 10px;
    margin-right: 20px;
}

.repair-title-flex {
    display: flex;
    flex-wrap: wrap;
    flex-direction: initial;
}

.select-staff {
    margin-top: 5px;
    margin-bottom: 5px;
    margin-right: 5px;
}

.duzz-uploaded-files-list {
    margin-left: 10px;
}

.customertabsbottom {
    margin-bottom: 20px !important;
    margin-top: 20px !important;
}

.side-margins-customer-view {
    margin-left: 0px !important;
    padding-left: 0px !important;
}

.flex-stage {
    display: flex;
}

.submit-project-update-button:disabled {
    background-color: #dddddd !important;
    color: grey !important;
}



@media only screen and (min-width: 981px){
.resend-field-sub-container{
    margin-right: 80px;
}
}

@media only screen and (max-width: 981px){
.resend-field-sub-container{
    margin-right: 10px;
}
}




/*INVOICE*/

.payment-status-text {
  padding: 10px;
  color: #ff423a;
  width: 200px;
  font-weight: 700;
  border: 3px solid;
  border-radius: 10px;
}

input[readonly], select[disabled] {
    background-color: #ffc9c9 !important;
}


.invoice-estimate-table-container {
  border: 3px solid #347eff;
  border-radius: 0 0 20px 20px;
  padding: 20px;
}
@media only screen and (max-width:650px) {
    .tabcontent th{
        display: none;
    }
    }

.invoice-fields-wrapper input {
    width: 100%;
    box-sizing: border-box;
}

.invoice-fields-wrapper input[type='number'] {
    -moz-appearance: textfield;
}

.invoice-fields-wrapper input[type='number']::-webkit-inner-spin-button,
.invoice-fields-wrapper input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}


.invoice-fields-wrapper input[type='number'] {
    text-align: right;
}


.invoice-info-wrapper td,
.invoice-fields-wrapper td{
    border: 0px solid rgba(255, 255, 255, 0.1);
      padding: 0 0px 0 0px;
}


.submit-button-class {
  width: 100%;
  height: 40px;
}
.invoice-fields-wrapper input[type="text"]{
    margin-left: 0px;
    max-width: 100% !important;
}


.staff-pages-container input[type="submit"],
.invoice-fields-wrapper input[type="submit"] {
    margin-bottom: 0px!important;
}

.custom-selected-tr input[type=text], 
.invoice-info-wrapper input[type=number],
.invoice-info-wrapper input[type=text], 
.invoice-info-wrapper select,
.invoice-fields-wrapper input[type=number],
.invoice-fields-wrapper input[type=password],
.invoice-fields-wrapper input[type=email],
.invoice-fields-wrapper input[type=text], 
.invoice-fields-wrapper input[type=time], 
.invoice-fields-wrapper input[type=url], 
.invoice-fields-wrapper input[type=week], 
.invoice-fields-wrapper input[type=tel],
.invoice-fields-wrapper select, 
.invoice-fields-wrapper textarea {
    background-color: #d7fde7;
    border-radius: 10px!important;
    padding: 8px;
    width: 98%!important;
    min-width: 98%!important;
    max-width: 98%!important;
}


.input-wrapper{
    width: 98%!important;
}

.invoice-fields-wrapper select{
    width: 90%!important;
    min-width: 90%!important;
    max-width: 90%!important;
}

.add-line-item-button {
  margin: 1px;
}

.add-line-item-button{
       background-color: #5fe38b; 
       border-radius: 10px;
}

.add-line-item-button:focus{
       background-color: #5fe38b; 
}

.add-line-item-button:hover{
       background-color: #9effbe !important; 
}

.remove-line-item-button{
    background-color: #ff423a!important;
}



.input-wrapper {
    position: relative;
    display: inline-block;
}

.prepend-icon {
    position: absolute;
    left: 5px;
    top: 50%;
    transform: translateY(-50%);
    background-color: #5fe38b;
padding: 7px;
border-radius: 10px 0px 0px 10px;
margin-left: -10px;
}

.append-icon {
    position: absolute;
    right: 5px;
    top: 50%;
    transform: translateY(-50%);
    background-color: #5fe38b;
    padding: 7px;
    border-radius: 0 10px 10px 0;
}

.input-wrapper input {
    padding-left: 20px;  /* or as needed, to make space for the $ sign */
    padding-right: 20px;  /* or as needed, to make space for the % sign */
}


.total-invoice-pricing-container td,
.invoice-pricing-container table, 
.invoice-pricing-container td{
    border: 0px solid rgba(255, 255, 255, 0);
    padding: 0px;
    margin: 0px;
}

.invoice-pricing-container {
  padding: 5px 30px 0px 30px;
  box-shadow: 0px 0px 20px 1px rgba(125, 125, 125, 0.1);
  border-radius: 15px;
  margin-top: 10px;
  max-width: 600px;
}

.total-invoice-pricing-container{
      padding: 30px;
      padding: 5px 30px 0px 30px;
      max-width: 600px;
}

.total-invoice-pricing-container td{
    padding-top: 5px;
    padding-bottom: 5px;
}

.pricing-border-top-total-price td{
    border-top: 1px solid #ccc !important;
}

.pricing-invoice-header{
          padding: 30px;
        width: 600px;
}

.invoice-align-right{
    text-align: right;
}

.progress-sub-title{
    font-size: 20px;
    font-family: "Baloo Chettan 2" !important;
    font-weight: 600;
}

.pricing-invoice-header {
  font-size: 25px;
  font-family: "Baloo Chettan 2" !important;
  font-weight: 600;
}

.pricing-invoice-sub {
  color: gray;
}

.page-id-9921 .entry-title, .page-id-9923 .entry-title, .page-id-9924 .entry-title, .page-id-9926 .entry-title, .page-id-9920 .entry-title{
    display: none;
}

.acf-error-message {
  padding: 10px;
  color: #ff423a;
}

.pricing-invoice-sub td{
    padding-bottom: 10px!important;
}


.view-mobile-green{
  background-color: #5fe38b;
  width:  3px;
  border-radius: 10px;
  padding: 0 0px 0 0px;
}

.invoice-info-wrapper .view-mobile-green{
    width: 10px;
}

.invoice-total-border-green{
    background-color: #d7fde7;
    padding: 8px !important;
    border-radius: 10px !important;
    text-align: right;
    border: 3px solid white!important;
}

.invoice-total-border-green td{
    border: 3px solid white!important;
}

.invoice-total-border{
        padding: 8px !important;
        font-weight: 800;
}


    td {
        display: table-cell;
    }

.total-invoice-pricing-container table,
.invoice-pricing-container table {
  table-layout: fixed;
  width: 100%;
}

.total-invoice-pricing-container td:first-child,
.total-invoice-pricing-container th:first-child,
.invoice-pricing-container td:first-child, 
.invoice-pricing-container th:first-child {
  width: 30%;
}

.total-invoice-pricing-container td:nth-child(2),
.total-invoice-pricing-container th:nth-child(2),
.invoice-pricing-container td:nth-child(2), 
.invoice-pricing-container th:nth-child(2) {
  width: 70%;
}

.invoice-feed-title {
    margin-top: 0px;
    margin-bottom: 0px;
    font-size: 25px;
    font-weight: 900;
    color: #FF433A;
    height: 30px;
        font-family: "Baloo Chettan 2" !important;

}


    </style>
    <?php
}


}
