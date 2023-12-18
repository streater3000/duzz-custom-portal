=== Duzz Custom Portal ===
Contributors: streater3000, dannycooper
Tags: portal, chat, stripe, payments, customer portal, payment
Requires at least: 5.7
Tested up to: 6.4.3
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Instantly connect with your customers and keep the conversation going with Duzz Custom Portal.

== Description ==

For urgent tech support:
[Tech Support](https://duzz.io/support/)

[Duzz Custom Portal ](https://duzz.io/) is designed to help small service-based businesses dynamically interact with their customers right from their website. Our Stripe payments integration does what no other plugin can do: make customized payments on a project by project basis.

Let your website work for you. Engage in real-time chats, foster stronger customer relationships, and enhance your revenue potential, all in one unified platform. With Duzz Custom Portal, you're not just waiting around for contact form inquiries – you're proactively reaching out, and most importantly, offering a payment flexibility that no other plugin currently offers.

Distinctively standing apart from other Stripe-integrated platforms, Duzz Custom Portal pioneers a dynamic payment system. Instead of restricting businesses to fixed product prices or generic donation sums, our portal is the first of its kind to empower businesses with per-project invoicing. Tailor your charges, adding line items for every project facet, and presenting your clients with a clear, customized invoice. 

This powerful, highly customizable plugin comes ready to use out of the box, transforming your website from a static display into a customer outreach and connection tool.

**Featsres**

- Stripe payments integration
- Status Feed for customers to track their project progress like tracking a FedEx Package
- Progress Bar showing each stage as the project progresses
- Data connections between Advanced Custom Fields (ACF) and WP Forms
- Tag customers in the feed to send them email updates
- Invite customers to the project
- Bot updates when project is updated
- Auto welcome message for website visitors

**Security**
Duzz Custom Portal operates on a zero trust system, so your customers aren't logging into the Wordpress backend, which could be a security vulnerability. Customers don't create an account or password, a factor that often leads to a 30% loss in sales as customers don't want to create an account. Instead, they get a unique tracking number and link, just like a FedEx Package. The plugin is designed to allow the addition of a password system if needed in the future. 

For security purposes, limited data is included on the page your customers view. But it is possible to update the code if you know PHP so that fields and data are viewable by the customer. That’s not currently included in the plugin. Duzz is only as secure as your hosting provider and Wordpress security plugins. Avoid storing sensitive customer information. Do not collect social security numbers or other personal ID numbers.

**Requirements**

While Duzz Custom Portal works out of the box, for full functionality, we suggest the following:
- Advanced Custom Fields (ACF) basic version from Wordpress repository
- WPForms basic for simple fields for Name and Email
- WPForms Plus for fancy fields that allow full Duzz functionality
- Hosting provider should allow PHP emails as Duzz does not currently use SMTP. -Hosting provider that allows you to turn off server side caching. 

**Choosing a hosting provider**

Because of these extra requirements for Duzz Custom Portal, many hosting providers might not be compatible. Your site will become more dynamic and increase the usage the CPU usage of your site as your site will get more usage with all the customer interactions. 

The main issue though is that since Duzz Custom Portal makes your site more dynamic and interactive, any hosting provider with heavy server side caching could prevent customer pages from updating. Avoid GoDaddy Managed Wordpress as they have the most heavy server side caching. GoDaddy Managed Wordpress does not allow you to turn off server side caching.

We recommend Siteground as they allow you to turn off server side caching with their SG Optimizer plugin. They also allow for 300 PHP emails per hour. And they are recommended for security purposes. 

If you plan to use Siteground, use our referral link to support us:
[Click here for our referral](https://www.siteground.com/index.htm?afcode=5140527bb15b2e0193acb4f4b6051009)

NOTE: Let us know if Duzz works with your hosting provider or not! We will create a list on our website of compatible hosting providers.


== External Libraries ==

This plugin utilizes Composer for dependency management, which means an autoloader is in place for handling PHP classes and libraries. If you're a developer wanting to understand the structure or extend the functionality, be aware of this setup.

**Composer**:
- Used For: Dependency management for PHP.
- Source: [https://getcomposer.org/](https://getcomposer.org/)
- Autoloader Path: `duzz-custom-portal/vendor/autoload.php`

**Featherlight**:
- Used For: Lightweight jQuery lightbox.
- Source: [https://github.com/noelboss/featherlight](https://github.com/noelboss/featherlight)
- Included Version: `duzz-custom-portal/js/featherlight.js`

**Stripe**:
- Used For: Handling online payments.
- Source: [https://github.com/stripe/stripe-php](https://github.com/stripe/stripe-php)
- Included Version: `duzz-custom-portal/vendor/stripe/stripe-php`

**Select2**:
- Used For: Making long dropdowns more user-friendly.
- Source: [https://github.com/select2/select2](https://github.com/select2/select2)
- Included Version: `duzz-custom-portal/vendor/select2/select2`

Feel free to review the original, non-altered source on the provided links. These libraries are included in this plugin in good faith, with the intention of enhancing functionality, and all rights and credits belong to their respective authors.

== Third-Party Service Integration==

**Stripe**

Our plugin integrates with Stripe to provide enhanced payment functionalities. This means that some data might be transmitted to Stripe's servers when using the payment features of our plugin.

*Service Provider:* Stripe

*Service Link:* [Stripe](https://stripe.com/)

*Purpose of Integration:* Our plugin uses Stripe to handle payments and payment-related functionalities, ensuring secure and efficient transactions for users.

*Data Shared:* The plugin sends payment-related data, such as transaction details and card information, to Stripe. No personal data is stored by our plugin; all sensitive data is handled by Stripe directly.

*Terms of Use:* You can review Stripe's terms of service [here](https://stripe.com/legal).

*Privacy Policy:* For more details on how Stripe handles your data, please refer to their privacy policy [here](https://stripe.com/privacy).

We encourage all users to review Stripe's terms and privacy policy to fully understand how your data is used and protected. By using the payment features of our plugin, you agree to Stripe's terms of service and privacy policy.

== Legal & Security Notice Regarding Stripe Integration ==

**Integration Permission:**

Stripe provides a robust API and encourages developers to integrate its services with their applications, plugins, or platforms. Our integration with Stripe in the Duzz Custom Portal plugin is based on the permissions and guidelines provided by Stripe for such integrations.

**No Direct Association with Stripe:**

While we have integrated Stripe's services into our plugin for enhanced payment functionalities, it's essential to clarify that we have no direct association, partnership, or endorsement from Stripe. Any questions, concerns, or issues related to Stripe's services should be directed to Stripe's official support channels.

**Security Assurance:**

Stripe is known for its rigorous security standards, and we're committed to adhering to them. Here's how we ensure the security of your transactions:

1. **Credit Card Information:** At no point does our plugin or any component of your WordPress site store credit card details. When a user inputs their credit card information, it's directly processed by Stripe. This ensures the highest level of security, as all sensitive data is managed directly by Stripe, which employs state-of-the-art security measures.

2. **Compliance with Stripe's Security Guidelines:** Our integration with Stripe strictly follows their security guidelines. This means that any updates or security practices recommended by Stripe are implemented in our plugin to ensure consistent security.

3. **Data Handling:** While our plugin facilitates the payment process, all the crucial transaction data and processing are handled directly by Stripe. This means that we do not have access to sensitive transaction details, adding an additional layer of privacy and security for the end-users.

We always encourage users and administrators to keep plugins updated and periodically review and ensure the security of their website.

== Installation ==

Watch an installation tutorial video here:
[How to install](https://youtu.be/bzF-s90oczc?si=8sE7iagQyvbVBGp1)

And you can find the full documentation here: 
[Documentation](https://duzz.io/documentation/)


The instructions below have since been updated

**STEP BY STEP INSTALLATION INSTRUCTIONS**

1. Install Duzz. You’ll click Install and then Activate.
3. Once you click Activate, be patient as it takes about 10-20 seconds to activate.
4. Go to the Duzz Portal settings tab in the admin dashboard to view the main settings page which is just called ‘Duzz Portal’.
5. You’ll notice a few different settings sections on this page. First is ‘List Projects’ where you choose which columns you’ll want to display when viewing your projects list. The default choices are‘customer_email’, ‘website’, and ‘customer_name’. You can select any data you want to change the columns. ***NOTE: You may notice I have prevented some choices such as 'customer_address' in the table for formatting and technical purposes. But you'll be able to edit these when you view an individual project.***
6. In the Email Settings section, add your business email, your name, and company name. This affects auto messages and auto emails that Duzz uses so make sure that is updated before use.
7. Next, in the Welcome Message textarea box, create a welcome message for your customers. This will display in the feed when a site visitor creates a project so you instantly connect with them. Don’t write Hi to start it off because the auto message already says ‘Hi {customer_name}’ when they create a project.
8. Finally, the Project Page sections allows you to choose which data and fields to display on the admin view when viewing a customer project. Main Data should be the data you want to identify your project by. While it allows you to choose up to 4 for each of these, Main Data should only have one selection. If you have four selections for Main Data it won’t look good on the page. So add any other data you want to display in Header Data. Then the last three fields: Info Tab Fields, Updates Tab Fields and Funds Tab Fields you are selecting which fields you can edit data in. These fields are added to each tab. 
9. Click ‘Save Changes’.
10. Now you are ready.

**INSTRUCTIONS TO START USING DUZZ**

1. Now if you are ready to create a test project, go to Duzz Workspace by clicking Duzz Workspace on the Wordpress admin top menu bar. You'll see a table with fields at the top for adding 'Projects'. Enter information for a client and click 'Add'. If you enter an email for a client, this will not send them any emails until you later invite them.

2. Once the project is added, you'll notice there is a column that says the project is 'new'. These are automatic status updates that only work if ACF is installed according to the ACF instructions below.

3. Click on the project. You’ll then be able to view that project. You can click Invite at the top of the page if you are ready to invite your client to view the project. 

4. Review the project data first. You can type comments/messages into the feed and add to the feed. And update any fields.

5. When you post a message in the feed, a client will only receive email updates if they have been invited.

6. If you go to the 'Funds' section, you'll be able to create an estimate with line items. You'll notice as you enter data here it automatically calculates the costs and totals. The price is per unit, so add the number of units, and it will calculate the total.

7. When you click to send the Estimate, it will not email this to the client. It will just post it to the feed. So tag the customer in a new comment message if you want to update them. You can update the estimate too. And when your project is finished, you can change it to an invoice.

8. When you change the estimate to an invoice, it will add a button to the invoice that your customers can click to make a payment. Once your client makes the payment, it will note that in the feed and you'll no longer be able to update the funds tab. 

9. Now if your project is complete and Advanced Custom Fields is installed, update the 'project_status' to the final stage which should be 'Completed' and update the 'approved_status' to No, Closed - Lost, or Closed - Won and the project will be archived.

10. You can view all archived projects in the archive tab on the Duzz Workspace sidebar.

**INSTRUCTIONS TO SYNC WITH ACF**

Duzz Custom Portal works out of the box without Advanced Custom Fields, but we recommend integration for full capabilities that allow for a progress bar and project status.

Here are the steps to integrate.

1. You can have the ACF installed before or after Duzz is installed. But once they are both installed, go to the Duzz Portal settings and go to ACF Keys.

2. You'll notice it says 'Error: Duplicate field number found.' Below all the fields. This is because all the fields are installed as empty, so it would give a duplication error.

3. Now scroll to the bottom and look for 'Generate ACF Keys' and click 'Generate' which will generate random unique ACF keys for each field.

4. Now go to the very bottom and click 'Save' and this will save all your keys to ACF.

5. Now go to your ACF plugin and you'll notice it created the 'Duzz Fields' group with all your saved keys.

6. If you want to change any fields from the ones added automatically by Duzz to ACF, in ACF you'll have to get the ACF 'Field Keys'. Go to ACF and click on the field group where you are adding a field, click Screen Options at the top and then check 'Field Keys'. Now it will display a new column with all the keys. This is how you link ACF fields to Duzz fields. Then copy those keys and go to the Duzz settings 'ACF Keys' tab and paste that key into any field. Currently you can't add new fields in Duzz here so pick one you aren't using if you need to, or contact us for an update.

7. There are two ACF fields that Duzz is dependent on. The progress bar operates using the ‘project_status’ field and then 'approved_status'. These features do not work without ACF but Duzz will continue to function otherwise and you won't notice these features are missing either.

8. Do make 'approved_status' work, go back to ACF fields list in the My Group list. First, look for the field name ‘approved_status’. Click to edit ‘approved_status’ and then switch the field type to ‘Select’ and copy these four selections below: 
Under Review
Yes
No
Closed - Lost
Closed - Won
And add paste these into Choices.

9. Then to make the progress bar work, find the field name ‘project_status’ and click edit. You will also switch this field type to ‘Select’. This field controls your progress bar on customer project pages. If this is not updated, the progress bar will show an error. Unlike ‘approved_status’ though, you can customize ‘project_status’ a little more. You’ll be adding the steps in your process here. But you can add as many steps as you want. You should have 2 at the minimum. You can name your steps however you like. But it needs to be formatted like this: (number)(colon)(space)(name) so that it looks like this:
1: Welcome
2: Consultation
3: Working
4: Done
5: Payment
6. Completed
Copy and paste this into Choices if you want for guidance.

10. Now on the main Duzz settings page, in the section Acf Group, this is optional you can leave it as is if you don’t want to use. But if you want the bot to update the status feed when you update fields, you’ll need to get the ACF group name. If you go to ACF and hover over the group name, at the bottom of your browser, you’ll see a URL popup in tiny text. Inside it will say post= and whatever that number is, is the group number. Add this group number to the ACF Group ID 1 field in Duzz Settings. 

11. If there is a field you do not want added by the bot by the status feed, create a new ACF group and move a field to it and don’t include that group number in the Duzz settings Acf Group. As you begin to use Duzz you’ll probably see Bot messages you don’t want added, you can do it like that. Step 21 shows an alternative.

12. So alternatively, in the Remove Keys section in the Duzz Portal settings, you can add field keys from ACF that you do not want added by the Bot to the Duzz feed. This is easier if you only have a few fields you don’t want the Bot to update.


**INSTRUCTIONS TO SYNC WITH WP FORMS**

1. You'll want to install WP Forms if you want customers who visit your site to create a project themselves. Otherwise you'll have to create a project in the Duzz Workspace table and then invite your customers.

2. You can also create a more detailed admin form if the table fields are not enough when you create a project. On the Duzz Workspace page, if you created all the settings properly, you should see the sidebar menu with the ‘add project’ button. This will take you to the admin WP Form to create a project. Once you add it, it will redirect you back to Duzz Workspace where it will list all the projects.

3. Once WP Forms is installed, create two forms in WP Forms. One for your site visitors which should be named something like Clients Form. And then another form for you, the admin. The form number is displayed in the shortcode. For example: [wpforms id="9959”]. Now you will take that id “9959” or whatever it is, and go to the Duzz Portal tab at the top of the admin sidebar.

4. You’ll notice that there are two sub-menus: WP Forms Client and WP Forms Admin. At the top of each of those pages, there is a field ‘form_id’. You’ll paste your WP Forms form ids in there. This is how you’ll connect WP Forms to Duzz. If there are any problems with accessing a WP Form on the front end or a form submitting info to create a project, you may have made a mistake in connecting your forms here.

5. Each WP Forms field within a form also has an ID. If you click on each field you’ve added to your forms, you’ll see each field ID. You’ll add each of these field IDs to connect the fields to Duzz in the WP Forms Client and WP Forms Admin submenus. Again, if you have the Plus version of WP Forms, you can save some time by going to our website and requesting a a forms file you can upload that will automatically add fields with the default field numbers already added. 

6. Click Save Changes for each submenu page to save your field numbers whether you did it manually or uploaded our pre-created form.


**INSTRUCTIONS TO SYNC WITH STRIPE**

Our plugin offers a seamless integration with Stripe for improved payment functionalities. To integrate Stripe with the Duzz Custom Portal plugin, follow these steps:

1. Access the Duzz Custom Portal Admin Menu:
Navigate to your WordPress dashboard.
Click on the "Duzz Custom Portal" option in the admin menu.

2. Navigate to the Stripe Keys Tab:
Within the Duzz Custom Portal menu, select the "Stripe Keys" tab.

3. Configuring Live Keys:
**API Secret Key Live:**
Visit your Stripe Dashboard to retrieve your live API secret key.
Copy this key and paste it into the "API Secret Key Live" field in the "Stripe Keys" metabox.
**API Publishable Key Live:**
In your Stripe Dashboard, find the live API publishable key.
Copy and paste it into the "API Publishable Key Live" field in the "Stripe Keys" metabox.

4. Configuring Stripe Testing (Optional):
**Toggle Testing:**
Locate the metabox with the testing toggle in the "Stripe Keys" tab.
Turn testing "On" if you wish to use Stripe's testing environment. By default, this is set to "Off".
**API Secret Key Test:**
In your Stripe Dashboard, locate your test API secret key.
Copy this key and paste it into the "API Secret Key Test" field in the "Stripe Test" metabox.
**API Publishable Key Test:**
In your Stripe Dashboard, locate the test API publishable key.
Copy and paste it into the "API Publishable Key Test" field in the "Stripe Test" metabox.

5. Save Changes:
Ensure all changes are saved to finalize the Stripe integration with the Duzz Custom Portal plugin.

By completing these steps, you've successfully set up Stripe integration with the Duzz Custom Portal plugin. This allows you to manage payments efficiently and offer a superior user experience.

**Stripe Integration Guide: Advanced Features**

**Sending an invoice to customers for a Stripe payment**

To facilitate payments via Stripe, it's essential to send your customers an invoice. Here's a step-by-step guide on how to do this using Duzz Custom Portal:

1. Prerequisites:
A WordPress post type of "Project" (a custom post type created by the Duzz Custom Portal) must exist for a customer. This post type should also have an associated "project_id".

2. Accessing Project Details:
Navigate to the Duzz Workspace in your dashboard.
Click on the desired project from the list to view its details.

3. Navigating to Funds Tab:
Inside the project details, you will notice three tabs.
Click on the "Funds" tab.

4. Creating an Estimate or Invoice:
Under the "Funds" tab, look for the dropdown select titled "type".
Choose either "Estimate" or "Invoice" based on your preference.
**Note:** It's recommended to initially create an "Estimate" to send to your customers for approval. Once approved, you can convert this "Estimate" into an "Invoice". Alternatively, you can directly create an "Invoice".
The appearance of both "Estimate" and "Invoice" is similar to the customer. However, the "Invoice" has a crucial "Pay Now" button.

5. Customer Payment:
When the customer clicks the "Pay Now" button on an Invoice, a popup will appear.
The popup provides fields for the customer to enter their credit card details.
**Important:** Credit card information is not stored on your website or within the Duzz Custom Portal. All transactions strictly adhere to Stripe's guidelines, ensuring the utmost security and reliability. Stripe manages the entire transaction process.

6. Payment Notification:
Upon a successful payment, your Stripe account will reflect the transaction.
Stripe sends a payment notification to the Duzz Custom Portal. This notification indicates a payment has been made, but it only displays the amount you entered in your Estimate/Invoice. Duzz or your website will not receive any other transaction details from Stripe.

**Creating and Managing a Stripe Account**

If you don't have a Stripe account yet, you'll need one to integrate payments with Duzz Custom Portal. Here's how to set it up:

1. Signup on Stripe:
Visit [Stripe's official website](https://stripe.com/).
Click on "Start Now" or "Sign Up" to create your account.

2. Account Setup:
Follow the on-screen instructions to set up your account, including business information and bank details.

3. Retrieving API Keys:
Once your account is set up, navigate to the API section in the Stripe dashboard.
Here, you'll find both your live and test API keys, which you'll need for the Duzz Custom Portal integration.

Remember always to keep your API keys secure and never share them publicly.

== Frequently Asked Questions ==

= Does this plugin require any additional plugins? =

No, this plugin works out of the box but works best with WP Forms and Advanced Custom Fields (ACF). When the plugin is installed, it automatically creates ACF fields if ACF is already installed.

= Do I need the Pro versions of these plugins? =

Only if you need the advanced capabilities of these plugins. Duzz works with the basic versions from the Wordpress repository.

= What kind of forms should I add in WP Forms? =

Once Duzz Custom Portal is installed, you should add two forms within WP Forms - one for customers to create a project, and one for you to create a project.

= Duzz creates ACF fields on installation but does it create WP Forms on installation? =

WP Forms backend makes it much more difficult to programmatically add forms and fields on plugin activation. You will either have to add the forms and fields manually. However, the WP Forms Plus version does allow you to transfer forms and fields created from one site to another. If you go to our website, we have a WP Forms file we can send you for free upon request and you can upload it to your site. It adds 2 forms, one for the customer and one for the admin that were created to atomically integrate with the Duzz Plugin so it can save you some time.

= Can I create accounts for my employees? =

Duzz basic from the Wordpress repository creates only one employee type called 'duzz_admin' that has full capabilities with no restrictions. So be careful who you trust to add to this. We want to give you everything we can for free and make it open source. However, this is a basic version of Duzz which does not include other employee types. You could add multiple ‘administrators’ or 'duzz_admin' but this is not recommended for security purposes.

= Is this just a chatbot? = 

No, chatbots aren’t good at keeping the conversation going after a conversation is finished. They are only really useful for urgent one time conversations. Duzz is more for connecting after a contact form has been submitted and to keep a customer updated over time. I have experimented with integrating with Intercom on my site which allows for both types of conversations, but I am not currently using Intercom and will only offer the integration if there is interest.

= Do you have any other integrations? = 

We plan to offer many integrations. Some have been tested. But our first offering we are keeping it simple to see what users want. We created a very modular approach on the backend with the code so it should be fairly easy to add anything.

We are focused on creating the best customer interactive software available: the perfect Wordpress Customer Portal. We will add features based on demand but we really want to open it up to other Wordpress developers to add their features so we can focus on the core product. We want to hear from you though as we navigate the direction going forward.

= Why is the Duzz Workspace on the front end pages and not the backend Wordpress admin pages? = 

For a few reasons. First, the customer facing page has a similar URL so that you can change the URL to see what they see. It is not possible to link to the customer pages from the backend.

Second, in the future if you decide to add our multi-role / user add-on, your employees won't have access to your Wordpress backend with a password-less zero trust system. So why not just have the same visual they will have so you know what you are getting?

= Will Duzz only support a password-less system? = 

No, it would be very easy to add passwords for Duzz customer and employee accounts. The original system was built with login passwords. But this opens your site up to security vulnerabilities with users using the same login system as your admin backend. We believe our password-less system is more secure. We recommend a hosting provider that has a proven security record. Customers also don't like creating accounts. Think about how much easier a FedEx tracking number is. Now you'll have the same type of project tracking system.

= Can customers view these admin pages? = 

No, they are password protected and redirect to the login page. However, make sure to remove the pages from the site index with an SEO plugin.

= Is Duzz mobile friendly? = 

Yes, we designed it for all devices. It has a mobile view for you and your customers.

== Screenshots ==

1. View Project

2. Create Invoice

3. Project List Archive

4. Project Status Feed

5. Projects List

6. Sent Invoice

7. Stripe Payment

8. Stripe Settings Dashboard

9. Update Project

10. Duzz Settings Dashboard

== Changelog ==
= 1.1.3 - 2023-12-17 =
* Bot email corrected in Duzz_Activation
*  Updated styling in Duzz_Class_Factory and Duzz_ProjectTabs for the payments section to improve mobile experience and so styling works on all themes
*  Duzz_Send_Payment updated for mobile and Duzz_ProjectTabs script updated to help do this
*  Added new function duzz_get_saved_acf_key in Duzz_Keys that is called in Duzz_ACF_Field
*  Fixed Duzz_Project_Update switch statement
*  Fixed payment calculation in duzz_get_line_items in Duzz_Processes and duzz_create_invoice_line_items in Duzz_Send_Payment
*  Fixed permalinks on activation by adding call to duzz_register_rewrite_rules in Duzz_Activation
*  Creating new dynamic admin settings creation system in Duzz_Admin_Menu_Items which is called in Duzz_Admin which changed all options names
*  Updated all hardcoded options names for the final time in: Duzz_ACF_Sync, Duzz_Activation, Duzz_Email, Duzz_Helpers, Duzz_Keys, Duzz_Processes, Duzz_Stripe_Checkout, Duzz_Stripe_Enqueue, Duzz_WP_Forms

= 1.1.2 - 2023-12-13 =
* Updated Duzz_ACF_Sync
*  Duzz_Admin_Settings_Sections
*  and Duzz_Keys so that ACF keys can be generated with a button on the ACF Keys settings page in the Duzz settings

= 1.1.1 - 2023-12-08 =
* Fixed admin_form_id and client_form_id data names in Duzz_Admin_Menu_Items.php so WP Forms integration with portal is fixed

= 1.1.0 - 2023-12-07 =
* Portal ready to use on installation without saving settings
*  Updated Duzz_Activation.php to add duzz_save_default_connector_settings for portal settings activation
*  Updated duzz_create_forms_connectors to change all Duzz_Forms_Connector parameters to duzz_settings
*  Updated duzz_settings_list_data from switch statements to an array to work with new portal settings

= 1.0.80 - 2023-11-30 =
* Fixes function duzz_create_invoice_line_items in Duzz_Send_Payment.php
*  Addeed margin bottom to comment__content in Duzz_Class_Factory.php

= 1.0.79 - 2023-11-29 (First Release on WordPress Repository) =
* fixed customer_message in Duzz_Processes
*  Fixed Duzz Admin settings backend duplicate error message HTML in Duzz_Admin_Settings_Sections and styling in Duzz_Class_Factory

= 1.0.78 – 2023-11-02 =
* Added customer message by Updating Duzz_Admin_Menu_Items,Duzz_Keys, and Duzz_Processes.
* Duzz_ACF_Sync fixed so it doesn’t create duplicate ACF groups

= 1.0.77 – 2023-11-01 =
* CSS updated.
* HTML/CSS classes added to Duzz_Send_Payment.php

= 1.0.76 – 2023-10-31 =
* minified featherlight file removed

= 1.0.75 – 2023-10-31 =
* Updated duzz_generate_invoice_table in Duzz_Processes to use Duzz_Invoice_Table to construct the invoice table.
* Updated Duzz_Invoice_Table to properly construct the invoice table.
* Updated generatePayNowButton in Duzz_Stripe_Checkout to use Duzz_Invoice_Table to construct the button.
* Updated button styling in Duzz_Class_Factory

= 1.0.73 – 2023-10-25 =
* Added Check for existing projects in duzz_check_for_existing_project function in src/Core/Duzz_Processes.php file
* Fixed URL formatting with add_rewrite_rule and add_rewrite_tag in src/Shared/Layout/Duzz_Layout.php file
* Removed clipboard.min.js file from select2 using composer.json file.

= 1.0.0 =
* Initial release.
* Modifications based on WordPress review.