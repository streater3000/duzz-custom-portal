# Duzz Custom Portal

**Contributors**: streater3000  
**Tags**: customer service, interaction, tracking, project management, chat, stripe, payments, portal, customer portal, crm, payment, invoice  
**Requires at least**: 5.7  
**Tested up to**: 6.3  
**Stable tag**: 1.0.76
**License**: GPLv2 or later  
**License URI**: [GNU General Public License v2.0](http://www.gnu.org/licenses/gpl-2.0.html)

Instantly connect with your customers and keep the conversation going with Duzz Custom Portal.

## Description

Duzz Custom Portal is designed to help small service-based businesses dynamically interact with their customers right from their website. This powerful, highly customizable plugin comes ready to use out of the box, transforming your website from a static display into a customer outreach and connection tool.

Distinctively standing apart from other Stripe-integrated platforms, Duzz Custom Portal pioneers a dynamic payment system. Instead of restricting businesses to fixed product prices or generic donation sums, our portal is the first of its kind to empower businesses with per-project invoicing. Tailor your charges with precision, adding line items for every project facet, and presenting your clients with a clear, customized invoice. 

Let your website work for you. Engage in real-time chats, foster stronger customer relationships, and enhance your revenue potential, all in one unified platform. With Duzz Custom Portal, you're not just waiting around for contact form inquiries – you're proactively reaching out, and most importantly, offering a payment flexibility that no other plugin currently offers.

## Features
- Status Feed for customers to track their project progress like tracking a FedEx Package
- Progress Bar showing each stage as the project progresses
- Data connections between Advanced Custom Fields (ACF) and WP Forms
- Tag customers in the feed to send them email updates
- Invite customers to the project
- Bot updates when project is updated
- Auto welcome message for website visitors

Whether you already have a customer or connected with them outside of your website, you can invite them to track the project progress. If a potential customer stumbles upon your website, you can initiate a chat right away to kickstart their project.

Unlike most sales software that focuses solely on closing sales, Duzz Custom Portal enables you to keep customers by letting them track their project progress until completion.

The plugin comes with a customizable progress bar and a status feed for chats and updates. Customers can be tagged in updates to send them email notifications. 

## Security
Duzz Custom Portal operates on a zero trust system, so your customers aren't logging into the Wordpress backend, which could be a security vulnerability. Customers don't create an account or password, a factor that often leads to a 30% loss in sales as customers don't want to create an account. Instead, they get a unique tracking number and link, just like a FedEx Package. The plugin is designed to allow the addition of a password system if needed in the future. 

For security purposes, limited data is included on the page your customers view. But it is possible to update the code if you know PHP so that fields and data are viewable by the customer. That’s not currently included in the plugin. Duzz is only as secure as your hosting provider and Wordpress security plugins. Avoid storing sensitive customer information. Do not collect social security numbers or other personal ID numbers.

## Requirements
While Duzz Custom Portal works out of the box, for full functionality, we suggest the following:
- Advanced Custom Fields (ACF) basic version from Wordpress repository
- WPForms basic for simple fields for Name and Email
- WPForms Plus for fancy fields that allow full Duzz functionality
- Hosting provider should allow PHP emails as Duzz does not currently use SMTP. -Hosting provider that allows you to turn off server side caching. 


## Choosing a hosting provider
Because of these extra requirements for Duzz Custom Portal, many hosting providers might not be compatible. Your site will become more dynamic and increase the usage the CPU usage of your site as your site will get more usage with all the customer interactions. 
The main issue though is that since Duzz Custom Portal makes your site more dynamic and interactive, any hosting provider with heavy server side caching could prevent customer pages from updating. Avoid GoDaddy Managed Wordpress as they have the most heavy server side caching. GoDaddy Managed Wordpress does not allow you to turn off server side caching. 
If you insist on using GoDaddy, try their more expensive Enhance - Web Hosting Plus.
If you are looking for a smaller, cheaper hosting provider, many don’t allow PHP emails or have caps on the quantity of PHP emails that can be sent per hour. So reach out to a hosting provider first to check as they don’t usually mention this on their website. Many will tell you that they only allow SMTP emails which we currently do not support.
We recommend Siteground as they allow you to turn off server side caching with their SG Optimizer plugin. They also allow for 300 PHP emails per hour. And they are recommended for security purposes. 
If you plan to use Siteground, use our referral link to support us:
[Click here for our referral](https://www.siteground.com/index.htm?afcode=5140527bb15b2e0193acb4f4b6051009)
NOTE: Let us know if Duzz works with your hosting provider or not! We will create a list on our website of compatible hosting providers.


## Other details
- When a project is created, it creates an ID number called a project_id. When you view the URL for a project, the URL would look like this: 
http://duzz.io/project/?project_id=8817231306050603
While the URL your customer views would look like this: 
http://duzz.io/your-project/?project_id=8817231306050603
- Duzz creates a ACF field called ‘archived’. The archived status is either a 1 or a 0. 
- When the ACF field  ‘approved_status’ is marked as Closed - Lost, it automatically archives the project.
- Duzz automatically creates all needed pages on plugin activation. It will also create pages for your WP Forms and automatically add the forms on the pages.
- For all the WP Forms and ACF fields connections in the Duzz Portal settings pages, you do not have to have field numbers and keys for every piece of data. If you are not using the field ‘website’ for example, you do not need to update the default value. Just save it as is.
- If you delete a field number or key from Duzz Settings that connects with WP Forms and ACF, and save it as empty, it will revert to the default value. It will never be saved as empty. If you don't use a field, just leave it as is with the default value as it won't effect anything if you are not using it.
- If you have duplicate values, Duzz will give you an error message. Change the values to make them unique and then click 'Save Changes'
- This plugin works best with WP Forms and Advanced Custom Fields installed. When the plugin is installed, the plugin automatically creates AFC fields if AFC is already installed. Once Duzz Custom Portal is installed, you should add two forms within WP Forms. One for customers to create a project and one for you to create a project. When you create a project it does not send an email yet to your client so you can load all your projects and check all the details before sending them an invite at a later time.
- The plugin creates a bot that posts automatic updates to the feed when the project is updated. You can choose which data stored by ACF is not added to the feed. 


## External Libraries

This plugin utilizes the following libraries:

- **Composer**: Dependency management for PHP.  
  - [Source](https://getcomposer.org/)
  - Autoloader Path: `duzz-custom-portal/vendor/autoload.php`

- **Featherlight**:
- Used For: Lightweight jQuery lightbox.
- Source: [https://github.com/noelboss/featherlight](https://github.com/noelboss/featherlight)
- Included Version: `duzz-custom-portal/js/featherlight.js`

- **Stripe**:
  - Used For: Handling online payments.
  - Source: [https://github.com/stripe/stripe-php](https://github.com/stripe/stripe-php)
  - Included Version: `duzz-custom-portal/vendor/stripe/stripe-php`

- **Select2**:
  - Used For: Making long dropdowns more user-friendly.
  - Source: [https://github.com/select2/select2](https://github.com/select2/select2)
  - Included Version: `duzz-custom-portal/vendor/select2/select2`

Feel free to review the original, non-altered source on the provided links. These libraries are included in this plugin in good faith, with the intention of enhancing functionality, and all rights and credits belong to their respective authors.


## Third-Party Service Integration

### Stripe

Our plugin integrates with Stripe to provide enhanced payment functionalities. This means that some data might be transmitted to Stripe's servers when using the payment features of our plugin.

- **Service Provider:** Stripe
- **Service Link:** [Stripe](https://stripe.com/)
- **Purpose of Integration:** Our plugin uses Stripe to handle payments and payment-related functionalities, ensuring secure and efficient transactions for users.
- **Data Shared:** The plugin sends payment-related data, such as transaction details and card information, to Stripe. No personal data is stored by our plugin; all sensitive data is handled by Stripe directly.
- **Terms of Use:** You can review Stripe's terms of service [here](https://stripe.com/legal).
- **Privacy Policy:** For more details on how Stripe handles your data, please refer to their privacy policy [here](https://stripe.com/privacy).

We encourage all users to review Stripe's terms and privacy policy to fully understand how your data is used and protected. By using the payment features of our plugin, you agree to Stripe's terms of service and privacy policy.

### Duzz Custom Portal: Configuring Stripe

Our plugin offers a seamless integration with Stripe for improved payment functionalities. To integrate Stripe with the Duzz Custom Portal plugin, follow these steps:

1. **Access the Duzz Custom Portal Admin Menu:**
   - Navigate to your WordPress dashboard.
   - Click on the "Duzz Custom Portal" option in the admin menu.
2. **Navigate to the Stripe Keys Tab:**
   - Within the Duzz Custom Portal menu, select the "Stripe Keys" tab.
3. **Configuring Live Keys:**
   - **API Secret Key Live:**
     - Visit your Stripe Dashboard to retrieve your live API secret key.
     - Copy this key and paste it into the "API Secret Key Live" field in the "Stripe Keys" metabox.
   - **API Publishable Key Live:**
     - In your Stripe Dashboard, find the live API publishable key.
     - Copy and paste it into the "API Publishable Key Live" field in the "Stripe Keys" metabox.
4. **Configuring Stripe Testing (Optional):**
   - **Toggle Testing:**
     - Locate the metabox with the testing toggle in the "Stripe Keys" tab.
     - Turn testing "On" if you wish to use Stripe's testing environment. By default, this is set to "Off".
   - **API Secret Key Test:**
     - In your Stripe Dashboard, locate your test API secret key.
     - Copy this key and paste it into the "API Secret Key Test" field in the "Stripe Test" metabox.
   - **API Publishable Key Test:**
     - In your Stripe Dashboard, locate the test API publishable key.
     - Copy and paste it into the "API Publishable Key Test" field in the "Stripe Test" metabox.
5. **Save Changes:**
   - Ensure all changes are saved to finalize the Stripe integration with the Duzz Custom Portal plugin.

By completing these steps, you've successfully set up Stripe integration with the Duzz Custom Portal plugin. This allows you to manage payments efficiently and offer a superior user experience.

## Stripe Integration Guide: Advanced Features

### Sending an invoice to customers for a Stripe payment

To facilitate payments via Stripe, it's essential to send your customers an invoice. Here's a step-by-step guide on how to do this using Duzz Custom Portal:

1. **Prerequisites:**
   - A WordPress post type of "Project" (a custom post type created by the Duzz Custom Portal) must exist for a customer. This post type should also have an associated "project_id".
2. **Accessing Project Details:**
   - Navigate to the Duzz Workspace in your dashboard.
   - Click on the desired project from the list to view its details.
3. **Navigating to Funds Tab:**
   - Inside the project details, you will notice three tabs.
   - Click on the "Funds" tab.
4. **Creating an Estimate or Invoice:**
   - Under the "Funds" tab, look for the dropdown select titled "type".
   - Choose either "Estimate" or "Invoice" based on your preference.
   - **Note:** It's recommended to initially create an "Estimate" to send to your customers for approval. Once approved, you can convert this "Estimate" into an "Invoice". Alternatively, you can directly create an "Invoice".
   - The appearance of both "Estimate" and "Invoice" is similar to the customer. However, the "Invoice" has a crucial "Pay Now" button.
5. **Customer Payment:**
   - When the customer clicks the "Pay Now" button on an Invoice, a popup will appear.
   - The popup provides fields for the customer to enter their credit card details.
   - **Important:** Credit card information is not stored on your website or within the Duzz Custom Portal. All transactions strictly adhere to Stripe's guidelines, ensuring the utmost security and reliability. Stripe manages the entire transaction process.
6. **Payment Notification:**
   - Upon a successful payment, your Stripe account will reflect the transaction.
   - Stripe sends a payment notification to the Duzz Custom Portal. This notification indicates a payment has been made, but it only displays the amount you entered in your Estimate/Invoice. Duzz or your website will not receive any other transaction details from Stripe.

### Creating and Managing a Stripe Account

If you don't have a Stripe account yet, you'll need one to integrate payments with Duzz Custom Portal. Here's how to set it up:

1. **Signup on Stripe:**
   - Visit [Stripe's official website](https://stripe.com/).
   - Click on "Start Now" or "Sign Up" to create your account.
2. **Account Setup:**
   - Follow the on-screen instructions to set up your account, including business information and bank details.
3. **Retrieving API Keys:**
   - Once your account is set up, navigate to the API section in the Stripe dashboard.
   - Here, you'll find both your live and test API keys, which you'll need for the Duzz Custom Portal integration.

Remember always to keep your API keys secure and never share them publicly.


## Legal & Security Notice Regarding Stripe Integration

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

## Duzz Custom Portal Installation

Watch an installation tutorial video here: [How to install](https://www.youtube.com/watch?v=RuUSgCTNfrs)

**STEP BY STEP INSTALLATION INSTRUCTIONS**
1. If you want to use Advanced Custom Fields, follow the instructions below "INSTRUCTIONS TO SYNC WITH ACF" but ACF is not required. Also, you can use WP Forms too. Follow the instructions below "INSTRUCTIONS TO SYNC WITH WP FORMS" but it is not required.
2. Now install Duzz. You’ll click Install and then Activate.
3. Once you click Activate, be patient as it takes about 10-20 seconds to activate. Clicking Activate again might creates additional ACF groups and fields if ACF is installed. So you may want to delete duplicates if you did this by accident.
4. Duzz automatically created a sidebar menu for the front end portal on plugin activation, but you’ll still have to make sure it is fully added. Go to the Wordpress Menu for Appearance on the admin sidebar and go to the submenu named ‘menus’. If you are creating an entirely new site, you may have to delete your plugin’s default menu. But if you already have create your own menus, select the sidebar menu at the top of the menu page, and then once you see these menu items: Add Project, Workspace, Messages, and Logout, click ‘Save Menu’ at the bottom.
5. Go back to the Duzz Portal settings tab in the admin dashboard to view the main settings page which is just called ‘Duzz Portal’.
6. You’ll notice a few different settings sections on this page. First is ‘List Projects’ where you choose which columns you’ll want to display when viewing your projects list. The default choices are‘customer_email’, ‘website’, and ‘customer_name’. You can select any data you want to change the columns. ***NOTE: You may notice I have prevented some choices such as 'customer_address' in the table for formatting and technical purposes. But you'll be able to edit these when you view an individual project.***
17. In the Email Settings section, add your business email, your name, and company name. This affects auto messages and auto emails that Duzz uses so make sure that is updated before use.
18. Next, in the Welcome Message textarea box, create a welcome message for your customers. This will display in the feed when a site visitor creates a project so you instantly connect with them. Don’t write Hi to start it off because the auto message already says ‘Hi {customer_name}’ when they create a project.
19. Finally, the Project Page sections allows you to choose which data and fields to display on the admin view when viewing a customer project. Main Data should be the data you want to identify your project by. While it allows you to choose up to 4 for each of these, Main Data should only have one selection. If you have four selections for Main Data it won’t look good on the page. So add any other data you want to display in Header Data. Then the last three fields: Info Tab Fields, Updates Tab Fields and Funds Tab Fields you are selecting which fields you can edit data in. These fields are added to each tab. 
23. Click ‘Save Changes’.
24. Now you are ready.

**INSTRUCTIONS TO START USING DUZZ**

1. Now if you are ready to create a test project, go to Duzz Workspace by clicking Duzz Workspace on the Wordpress admin top menu bar. You'll see a table with fields at the top for adding 'Projects'. Enter information for a client and click 'Add'. If you enter an email for a client, this will not send them any emaails until you later invite them.
2. Once the project is added, you'll notice there is a column that says the project is 'new'. These are automatic status updates that only work if ACF is installed according to the ACF instructions below.
3. Click on the project. You’ll then be able to view that project. You can click Invite at the top of the page if you are ready to invite your client to view the project. 
4. Review the project data first. You can type comments/messages into the feed and add to the feed. And update any fields.
5. If you type @ you'll be able to tag any other co-workers or your client. Your client will only recieve email updates if they are tagged, so that they are not flooded with emails for every update.
6. If you go to the 'Funds' section, you'll be able to create an estimate with line items. You'll notice as you enter data here it automatically calculates the costs and totals. The price is per unit, so add the number of units, and it will calculate the total.
7. When you click to send the Estimate, it will not email this to the client. It will just post it to the feed. So tag the customer in a new comment message if you want to update them. You can update the estimate too. And when your project is finished, you can change it to an invoice.
8. When you change the estimate to an invoice, it will add a button to the invoice that your customers can click to make a payment. Once your client makes the payment, it will note that in the feed and you'll no longer be able to update the funds tab. 
9. Now if your project is complete and Advanced Custom Fields is installed, update the 'project_status' to the final stage which should be 'Completed' and update the 'approved_status' to No, Closed - Lost, or Closed - Won and the project will be archived.
10. You can view all archived projects in the archive tab on the Duzz Workspace sidebar.


## INSTRUCTIONS TO SYNC WITH ACF

1. **Installing ACF Before Duzz**: If you want to use Advanced Custom Fields, you can install ACF first. When Duzz Custom Portal is activated, it will automatically create all necessary fields in ACF. A field group with the essential fields will be created upon Duzz activation.

2. **Installing ACF After Duzz**: If you decide to install ACF post-Duzz installation, you can still prompt automatic field creation by updating and saving the 'ACF Keys Connector' settings in the Duzz Custom Portal settings on the Admin menu. Ensure you modify a key for this to be effective. Update any ACF field key (make sure it's not left empty) and save. Duzz will subsequently establish the necessary field group and fields in ACF.

3. **Empty Fields**: Saving a field as empty will restore it to its default key.

4. **Key Duplication**: Duplicate keys will prevent saving and trigger an error message.

5. **Customizing Fields**: If you wish to modify any fields that Duzz added to ACF, you'll need the ACF 'Field Keys'. Within ACF, click on the relevant field group, select 'Screen Options' and enable 'Field Keys'. This displays the keys column. Copy these keys and input them into the Duzz settings 'ACF Keys' tab. Currently, Duzz doesn't support adding new fields here. If required, choose a redundant field or contact our support.

6. **Dependent ACF Fields**: Duzz relies on two ACF fields. The ‘project_status’ field influences the progress bar, and the 'approved_status' field. Without ACF, these features will be absent, but Duzz will operate normally.

7. **Setting Up 'approved_status'**: Navigate to ACF fields, find 'approved_status', and edit it. Switch the field type to 'Select' and input the following choices: 
   - Under Review
   - Yes
   - No
   - Closed - Lost
   - Closed - Won

8. **Configuring the Progress Bar**: Locate the 'project_status' field in ACF and edit. This field controls the progress bar on customer project pages. Update this field to 'Select'. To prevent progress bar errors, ensure the proper configuration. You can customize this field to reflect your project steps. The format is as follows: (number):(space)(name), for instance:
   - 1: Welcome
   - 2: Consultation
   - 3: Working
   - 4: Done
   - 5: Payment
   - 6: Completed

9. **ACF Group Setup in Duzz**: On Duzz's main settings page, the 'Acf Group' section is optional. If you intend to use automated bot status updates based on field changes, you'll need the ACF group name. In ACF, hover over the group name and note the URL's 'post=' parameter. The subsequent number is the group ID. Input this into the 'ACF Group ID 1' field in Duzz Settings.

10. **Managing Bot Status Updates**: If certain fields shouldn't be updated by the bot in the status feed, move them to a new ACF group and exclude that group's number in Duzz settings. Alternatively, use the 'Remove Keys' section in Duzz settings to list the ACF field keys you'd prefer the bot not to update.



## INSTRUCTIONS TO SYNC WITH WP FORMS

1. **Installing WP Forms**: To allow customers visiting your site to initiate projects, it's advisable to install WP Forms. Otherwise, the only option is to manually create a project within the Duzz Workspace table and subsequently send invites to your customers.

2. **Using Admin Form for Project Creation**: If the standard table fields are inadequate, you can craft a detailed admin form for project initiation. If set up correctly, the Duzz Workspace page should display a sidebar menu with an ‘add project’ button. Activating this button redirects you to the admin WP Form to create a project. Once executed, it takes you back to the Duzz Workspace, displaying an updated list of all projects.

3. **Creating Forms in WP Forms**: Post-WP Forms installation, generate two distinct forms within the platform:
   - **Clients Form**: Tailored for site visitors.
   - **Admin Form**: Exclusively for administrative use.
   
   Each form is represented by a unique shortcode (e.g., [wpforms id="9959”]). Extract the form ID from this shortcode (“9959” in this instance) and navigate to the 'Duzz Portal' tab on the admin sidebar.

4. **Linking WP Forms with Duzz**: Within the Duzz Portal, two sub-menus are apparent: 'WP Forms Client' and 'WP Forms Admin'. Both these pages house a ‘form_id’ field at the top. Inject your WP Forms form IDs here to establish a connection between WP Forms and Duzz. If any issues arise related to WP Form accessibility on the frontend or info submission for project initiation, there's a possibility of an error in this linkage.

5. **Connecting Fields**: Every field within a WP Form possesses a unique ID. Accessing each field discloses this ID. In the 'WP Forms Client' and 'WP Forms Admin' sub-menus, these field IDs are essential to synchronize the fields with Duzz. For users with the 'Plus' version of WP Forms, a shortcut exists: you can obtain a pre-configured forms file from our website. This file, upon upload, will automatically populate fields, pre-loaded with default field numbers.

6. **Saving Changes**: For both 'WP Forms Client' and 'WP Forms Admin' sub-menus, remember to click 'Save Changes' to retain manually inputted or uploaded form details.


## Frequently Asked Questions

### Does this plugin require any additional plugins?

No, this plugin works out of the box but is optimized with WP Forms and Advanced Custom Fields (ACF). Upon installation, it auto-generates ACF fields if ACF is pre-installed.

### Do I need the Pro versions of these plugins?

Duzz is compatible with the basic versions from the WordPress repository. However, the Pro versions are necessary only if you're seeking their advanced features.

### What kind of forms should I add in WP Forms?

Post-installation of Duzz Custom Portal, create two forms within WP Forms: one for customers initiating a project, and another for your administrative use.

### Duzz creates ACF fields on installation but does it create WP Forms on installation?

Due to WP Forms backend constraints, forms and fields cannot be programmatically added upon plugin activation. Manual input is necessary. For users with WP Forms Plus, transferring forms and fields between sites is feasible. Our website offers a WP Forms file, available for free upon request, which can be uploaded to your site to save time.

### Can I create accounts for my employees?

The basic Duzz version from the WordPress repository offers a singular employee type titled 'duzz_admin' with unrestricted capabilities. Exercise caution with its allocation. This basic version excludes other employee types. Assigning multiple ‘administrators’ or 'duzz_admin' is discouraged due to security considerations.

### Is this just a chatbot?

No, Duzz extends beyond one-time urgent conversations catered to by chatbots. It is designed to maintain long-term interactions post a contact form submission. Integration with platforms like Intercom is on the horizon, pending user interest.

### Do you have any other integrations?

We are exploring multiple integrations and are adopting a user-centric approach to determine priorities. Our primary goal is to develop the quintessential WordPress Customer Portal, inviting input from other WordPress developers while we enhance the core product.

### Why is the Duzz Workspace on the front end pages and not the backend WordPress admin pages?

A few reasons:
- Enhanced URL accessibility.
- Future prospects of introducing a multi-role/user add-on, making backend access redundant.

### Will Duzz only support a password-less system?

No, though Duzz's system is crafted without password requirements for enhanced security, it is flexible enough to incorporate passwords for Duzz customer and employee accounts.

### Can customers view these admin pages?

No, these pages are password-protected, redirecting unauthorized users to the login page. Ensure their exclusion from site indexing via an SEO plugin.

### Is Duzz mobile friendly?

Yes, Duzz is designed for cross-device compatibility, ensuring a seamless experience on mobile for users and administrators.


## Screenshots

1. **Project Page**: Showcases an individual project for a customer, facilitating updates.
2. **Duzz Workspace Page**: Lists all ongoing projects. Projects can be accessed with a click.
3. **Status Feed**: Displays all messages for all projects. Clicking on a message will redirect to its corresponding project.
4. **Project Search Page**: Allows customers to retrieve their projects using their unique ID (project_id) if misplaced.
5. **Settings Menu**: Duzz's settings interface to configure site preferences. Additionally, there's a link at the top to the Duzz Workspace for quick access to projects.



## Changelog
### 1.0.76 (2023-10-31)
- **minified**:
  - featherlight file removed

### 1.0.76 (2023-10-31)
- **Updated**:
  - duzz_generate_invoice_table in Duzz_Processes to use Duzz_Invoice_Table to construct the invoice table.
- **Updated**:
  - Duzz_Invoice_Table to properly construct the invoice table.
- **Updated**:
  - generatePayNowButton in Duzz_Stripe_Checkout to use Duzz_Invoice_Table to construct the button.
- **Updated**:
  - button styling in Duzz_Class_Factory

### 1.0.73 (2023-10-25)
- **Added**:
  - Check for existing projects in duzz_check_for_existing_project function in src/Core/Duzz_Processes.php file.
- **Fixed**:
  - URL formatting with add_rewrite_rule and add_rewrite_tag in src/Shared/Layout/Duzz_Layout.php file.
- **Removed**:
  - clipboard.min.js file from select2 using composer.json file.

### 1.0.0
- Initial release.
- Updates based on WordPress review.

