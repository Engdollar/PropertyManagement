<?php

namespace Workdo\PropertyManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Workdo\LandingPage\Entities\MarketplacePageSetting;


class MarketPlaceSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $module = 'PropertyManagement';

        $data['product_main_banner'] = '';
        $data['product_main_status'] = 'on';
        $data['product_main_heading'] = 'PropertyManagement';
        $data['product_main_description'] = '<p>Efficiently manage properties, leases, tenants, and maintenance tasks with our integrated Property Management Module, offering a streamlined solution for property owners. The lease identifies the specific property or unit being rented. It may include a description of the property, including the number of bedrooms, bathrooms, and other relevant details.</p>';
        $data['product_main_demo_link'] = '#';
        $data['product_main_demo_button_text'] = 'View Live Demo';
        $data['dedicated_theme_heading'] = 'One Solution For All Your Property Management Needs';
        $data['dedicated_theme_description'] = '<p>Simplify property management tasks seamlessly with our comprehensive module, covering everything from tenant management  to maintenance requests and financial reporting.</p>';
        $data['dedicated_theme_sections'] = '[
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "The Smartest Way To Manage Your Property Management",
                                                    "dedicated_theme_section_description": "Transform property management into a streamlined process with our robust module, designed to handle property details, tenant relationships, lease agreements, and maintenance requests with ease.",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": "Manage Key Tenant Matters Easily",
                                                        "description": "Create a profile for every tenant and track their key information, including property unit and document. Update and change their information in just a few clicks."
                                                    },
                                                    "2": {
                                                    "title": "Lease Renewal",
                                                        "description": "At the end of the lease term, the parties may choose to renew the lease, negotiate new terms, or terminate the lease according to the specified process."
                                                    },
                                                    "3": {
                                                    "title": "Manage All Your Property Management Tasks Quickly And Easily From One Place",
                                                        "description": "Control and keep track of your invoices with ease. Enhance property oversight and maximize efficiency with our user-friendly Property Management Module, offering a centralized platform for lease management, rent collection, and property maintenance."
                                                    }
                                                    }
                                                },
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "Manage Your Property From One Place",
                                                    "dedicated_theme_section_description": "<p><\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": "Oprate a Fast and Easy Property Management process",
                                                        "description": "Make your property lease process fast and convenient for your tenants. select date of when you want to rent property and select the unit your tenants want to stay and after click on Book Now."
                                                    },
                                                    "2": {
                                                    "title": "Manage Your Payments Easily",
                                                        "description": "Get paid for work done, fast. Integrate several payment options for diverse tenants and make the payment process stress-free. Easily safeguard your tenants’ payment by using Stripe, PayPal, Flutterwave, and more."
                                                    },
                                                    "3": {
                                                    "title": "Get Instant Notifications",
                                                        "description": "Integrate the Twilio app and never miss an important notification again. Get notified when property unit booking are completed and get notifications about new property unit booking sent to your mobile phone via text."
                                                    }
                                                    }
                                                },
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "Modify Vital Property Management Info With Ease",
                                                    "dedicated_theme_section_description": "<p>Modify and update your generated property management property units with ease. Add new property, unit and his details without stress. Property Management allows you to create and maintain the data of each tenant. You get access to all essential information through a well-maintained format.<\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": null,
                                                        "description": null
                                                    }
                                                    }
                                                },
                                                {
                                                    "dedicated_theme_section_image": "",
                                                    "dedicated_theme_section_heading": "Essential Information At Your Fingertips",
                                                    "dedicated_theme_section_description": "<p>Optimize your property portfolio effortlessly using our Property Management Module, featuring intuitive tools for lease administration, tenant communication, and real-time performance analytics.<\/p>",
                                                    "dedicated_theme_section_cards": {
                                                    "1": {
                                                        "title": null,
                                                        "description": null
                                                    }
                                                    }
                                                }
                                            ]';
        $data['dedicated_theme_sections_heading'] = '';
        $data['screenshots'] = '[{"screenshots":"","screenshots_heading":"Property Management"},{"screenshots":"","screenshots_heading":"Property Management"},{"screenshots":"","screenshots_heading":"Property Management"},{"screenshots":"","screenshots_heading":"Property Management"},{"screenshots":"","screenshots_heading":"Property Management"}]';
        $data['addon_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['addon_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['addon_section_status'] = 'on';
        $data['whychoose_heading'] = 'Why choose dedicated modulesfor Your Business?';
        $data['whychoose_description'] = '<p>With Dash, you can conveniently manage all your business functions from a single location.</p>';
        $data['pricing_plan_heading'] = 'Empower Your Workforce with DASH';
        $data['pricing_plan_description'] = '<p>Access over Premium Add-ons for Accounting, HR, Payments, Leads, Communication, Management, and more, all in one place!</p>';
        $data['pricing_plan_demo_link'] = '#';
        $data['pricing_plan_demo_button_text'] = 'View Live Demo';
        $data['pricing_plan_text'] = '{"1":{"title":"Pay-as-you-go"},"2":{"title":"Unlimited installation"},"3":{"title":"Secure cloud storage"}}';
        $data['whychoose_sections_status'] = 'on';
        $data['dedicated_theme_section_status'] = 'on';

        foreach($data as $key => $value){
            if(!MarketplacePageSetting::where('name', '=', $key)->where('module', '=', $module)->exists()){
                MarketplacePageSetting::updateOrCreate(
                [
                    'name' => $key,
                    'module' => $module

                ],
                [
                    'value' => $value
                ]);
            }
        }
    }
}
