<?php

namespace App\Models;
use Log;

class RebImport {

    private $mls_select;
    public static $mls_list;


    /**
     * Construct Method.
     */
    function __construct()
    {

        $this->mls_select = implode(',', array(
            'ListingId',
            'ListingKeyNumeric',
            'PhotosCount',
            'StandardStatus',
            'ModificationTimestamp',

            'ListOfficeMlsId',
            'ListAgentMlsId',
            'ListAgentFirstName',
            'ListAgentLastName',
            'ListOfficeName',

            'ListPrice',
            'Country',
            'StateOrProvince',
            'City',
            'PostalCode',
            'StreetNumber',
            'StreetNumberNumeric',
            'StreetDirPrefix',
            'StreetName',
            'StreetSuffix',
            'StreetDirSuffix',
            'UnitNumber',
            'CountyOrParish',
            'MLSAreaMajor',

            'Latitude',
            'Longitude',

            'PropertyType',
            'PropertySubType',

            'BedroomsTotal',
            'BathroomsFull',
            'BathroomsHalf',

            'GarageSpaces',
            'ArchitecturalStyle',
            'YearBuilt',
            'LivingArea',
            'LotSizeSquareFeet',
            'PricePerSquareFoot',

            'PublicRemarks',

            'FireplaceYN',
            'CoolingYN',
            'HeatingYN',
            'InteriorFeatures',
            'Flooring',
            'Roof',
            'Appliances',
            'FoundationDetails',
            'ExteriorFeatures',

            'ElementarySchool',
            'MiddleOrJuniorSchool',
            'HighSchool',
            'HighSchoolDistrict',

            'AssetClass'
        ));
        $this::$mls_list = implode(',', array(
            'ListingId',
            'ListingKeyNumeric',
            'PhotosCount',
            'StandardStatus',
            'ModificationTimestamp',

            'ListOfficeMlsId',
            'ListAgentMlsId',
            'ListAgentFirstName',
            'ListAgentLastName',
            'ListOfficeName',

            'ListPrice',
            'Country',
            'StateOrProvince',
            'City',
            'PostalCode',
            'StreetNumber',
            'StreetNumberNumeric',
            'StreetDirPrefix',
            'StreetName',
            'StreetSuffix',
            'StreetDirSuffix',
            'UnitNumber',
            'CountyOrParish',
            'MLSAreaMajor',

            'Latitude',
            'Longitude',

            'PropertyType',
            'PropertySubType',

            'BedroomsTotal',
            'BathroomsFull',
            'BathroomsHalf',

            'GarageSpaces',
            'ArchitecturalStyle',
            'YearBuilt',
            'LivingArea',
            'LotSizeSquareFeet',
            'PricePerSquareFoot',

            'PublicRemarks',

            'FireplaceYN',
            'CoolingYN',
            'HeatingYN',
            'InteriorFeatures',
            'Flooring',
            'Roof',
            'Appliances',
            'FoundationDetails',
            'ExteriorFeatures',

            'ElementarySchool',
            'MiddleOrJuniorSchool',
            'HighSchool',
            'HighSchoolDistrict',

            'AssetClass'
        ));


        // add_action('wp', array($this, 'reb_property_action'));
        // add_action('reb_property_hook',  array( $this, 'mls_import') );
        // update_option('reb_mls_flag', false, false);
        // update_option('reb_mls_abort_flag', true, false);
        // delete_option('reb_mls_flag');
        // delete_option('reb_mls_abort_flag');
        // delete_option('reb_property_ModDate');
        // delete_option('reb_mls_offset');


        /*
        * Disables storing of meta data values in core meta tables where a custom
        * database table has been defined for fields. Any fields that aren't mapped
        * to a custom database table will still be stored in the core meta tables.
        */
        // add_filter('acfcdt/settings/store_acf_values_in_core_meta', '__return_false');

        /*
        * Disables storing of ACF field key references in core meta tables where a custom
        * database table has been defined for fields. Any fields that aren't mapped to a
        * custom database table will still have their key references stored in the core
        * meta tables.
        */
        // add_filter('acfcdt/settings/store_acf_keys_in_core_meta', '__return_false');

        /*
        * Thes two lines will deactivate ACFs 3rd party filters on data that is passed
        * to or retreived from a custom database table. They will not disable the filters
        * on data being stored in the core meta tables.
        */
        // add_filter('acfcdt/settings/allow_acf_update_value_filters', '__return_false');
        // add_filter('acfcdt/settings/allow_acf_load_value_filters', '__return_false');

        // add_filter('cron_schedules', array($this, 'reb_cron_schedules'));

        // add_action('rest_api_init', function () {
        //     register_rest_route('reb/v1', '/props_change_types/(?P<id>\d+)', array(
        //         'methods' => 'GET',
        //         'callback' => array($this, 'reb_props_change_types'),
        //     ));
        // });

        // add_action('rest_api_init', function () {
        //     register_rest_route('reb/v1', '/test_mls/(?P<id>\d+)', array(
        //         'methods' => 'GET',
        //         'callback' => array($this, 'test_mls'),
        //     ));
        // });

        // add_action('wp', array($this, 'rebuild_prop_action'));
        // add_action('rebuild_prop_hook',  array( $this, 'rebuild_prop') );

    }

    function init_rets()
    {

        global $rets;

        $config = new \PHRETS\Configuration;
        $config->setLoginUrl('https://pt.rets.crmls.org/contact/rets/login')
            ->setUsername('REBEES')
            ->setPassword('3thFpA%9')
            ->setRetsVersion('1.8');
        // ->setOption('use_post_method', true);

        $rets = new \PHRETS\Session($config);

        Log::info('rets init');
        // write_log('rets init');
    }

    function init_test()
    {

        global $rets;

        $config = new \PHRETS\Configuration;
        $config->setLoginUrl('https://pt.rets.crmls.org/contact/rets/login')
            ->setUsername('REBEES')
            ->setPassword('3thFpA%9')
            ->setRetsVersion('1.8');
        // ->setOption('use_post_method', true);

        $rets = new \PHRETS\Session($config);

        return $rets;

        Log::info('rets init');
        // write_log('rets init');
    }

    function mls_import()
    {

        $this->init_rets();
        $this->get_mls_properties_initial(25000);
        // if (get_option('reb_mls_abort_flag')) {
        //     Log::info('reb_mls_abort_flag is ON');
        //
        //     $timestamp = wp_next_scheduled('reb_property_hook');
        //     wp_unschedule_event($timestamp, 'reb_property_hook');
        //     wp_reschedule_event(time(), 'daily', 'reb_property_hook');
        //
        //     return;
        // }

        // if (!get_option('reb_mls_flag')) {
        //
        //     update_option('reb_mls_flag', true, false);
        //     // update_option('reb_mls_abort_flag', false, false);
        //
        //     $this->init_rets();
        //     // $this->test_request();
        //
        //     // /* Agents */
        //     // // $this->get_mls_agents(200);
        //
        //     // /* Properties */
        //     $this->get_mls_properties(100);
        //
        //     // /* Open House */
        //     // $this->get_mls_openhouse(500);
        //     // $this->clear_openhouse();
        //
        //     update_option('reb_mls_flag', false, false);
        //
        // } else {
        //     Log::info("mls_import() don't run flag is on");
        // }

    }

    // function test_request()
    // {
    //     global $rets;
    //
    //     Log::info('--------');
    //     Log::info('Start test MLS request');
    //
    //     try {
    //
    //         // $query = '(ListingKeyNumeric=362058079)';//(Matrix_Unique_ID=155947203+)';//,(ListOfficeMLSID=KWPT01,KWPT02),(StateOrProvince=TX)';
    //         $buletin = $rets->login();
    //         if ($buletin) {
    //
    //             // $classes = $rets->GetClassesMetadata('Property')->toArray();
    //             // // Log::info(print_r($classes, TRUE));
    //
    //
    //             // foreach($classes as $class) {
    //             //     // Log::info(print_r($item, TRUE));
    //             //     // $item = $item->toArray();
    //             //     // Log::info('=====================');
    //             //     Log::info(print_r($class['ClassName'], TRUE));
    //             // }
    //
    //             $result = $rets->Search('Property', 'ResidentialLease', '(ListingKeyNumeric=2193454)', ['Format' => 'COMPACT-DECODED', 'Select' => $this->mls_select, 'StandardNames' => 0, 'Limit' => 2, 'Offset' => 0]);
    //
    //             $total_res = $result->getTotalResultsCount();
    //             Log::info('Total results: ' . $total_res);
    //
    //             REB_mls::property_records_handler($result, 'ResidentialLease');
    //             // foreach ($result as $record) {
    //             //     // Log::info(print_r($record, TRUE));
    //
    //             //     $query = '(ResourceRecordKeyNumeric='.$record->get('ListingKeyNumeric').')';
    //             //     $photoUrls = $rets->Search("Media","Media", $query, ['Format' => 'COMPACT-DECODED','Select' => 'MediaURL,Order','StandardNames' => 0]);
    //             //     $photo_arr = $photoUrls->toArray();
    //             //     usort($photo_arr, function($a, $b) {
    //             //         return $a['Order'] <=> $b['Order'];
    //             //     });
    //             //     $images = array();
    //             //     if( !empty($photo_arr) ) {
    //             //         foreach ($photo_arr as $object) {
    //             //             // if ($index < 1) continue;
    //             //             if (preg_match('/\.(jpeg|jpg|png|gif)$/i', $object['MediaURL'])) {
    //             //                 $images[] = $object['MediaURL'];
    //             //             }
    //             //         }
    //             //     }
    //             //     // Log::info( print_r($images, true) );
    //             //     update_field('thumbnail', $images[0], $post_id);
    //             //     update_field('images', json_encode($images), $post_id);
    //
    //             // }
    //
    //             $rets->Disconnect();
    //
    //         } else {
    //             Log::info("Login problem");
    //         }
    //
    //
    //     } catch (\Exception $e) {
    //         throw new \RuntimeException($e->getMessage());
    //         Log::info('----START Exception----');
    //         Log::info($e->getMessage());
    //         Log::info('----END Exception----');
    //         update_option('reb_mls_flag', false, false);
    //     }
    //     Log::info('END test MLS request');
    //
    // }
    //
    // function get_mls_agents($limit = 200)
    // {
    //
    //     global $rets;
    //
    //     $Select = 'Matrix_Unique_ID,MatrixModifiedDT,AgentStatus,Office_MUI,FirstName,LastName,LicenseNumber,DirectWorkPhone,CellPhone,WebPageAddress,MemberNumber';
    //
    //     $reb_agent_ModDate = get_option('reb_agent_ModDate') ? get_option('reb_agent_ModDate') : '2000-01-01T00:00:00';
    //     $date = date('Y-m-d\TH:i:s', time());
    //     update_option('reb_agent_ModDate_temp', $date, false);
    //
    //     Log::info('--------');
    //     Log::info('Start Agent MLS Import');
    //     Log::info('Start date: ' . get_option('reb_agent_ModDate_temp'));
    //     Log::info('From Date: ' . $reb_agent_ModDate);
    //
    //     try {
    //
    //         $query = '(MatrixModifiedDT=' . $reb_agent_ModDate . '+),(AgentStatus=A),(OfficeMLSID=KWPT01,KWPT02)';
    //
    //         $buletin = $rets->login();
    //         if ($buletin) {
    //
    //             $result = $rets->Search('Agent', 'Agent', $query, ['Format' => 'COMPACT-DECODED', 'Select' => 'Matrix_Unique_ID', 'Limit' => 1, 'Offset' => 0]);
    //             $total = $result->getTotalResultsCount();
    //
    //             if ($total > 0) {
    //                 while ($total > 0) {
    //
    //                     if (get_option('reb_mls_abort_flag')) {
    //                         Log::info('reb_mls_abort_flag is ON');
    //
    //                         $timestamp = wp_next_scheduled('reb_property_hook');
    //                         wp_unschedule_event($timestamp, 'reb_property_hook');
    //                         wp_reschedule_event(time(), 'daily', 'reb_property_hook');
    //
    //                         return;
    //                     }
    //
    //                     Log::info('Total: ' . $total);
    //                     $records = $rets->Search(
    //                         'Agent',
    //                         'Agent',
    //                         $query,
    //                         ['Format' => 'COMPACT-DECODED', 'Select' => $Select, 'StandardNames' => 0, 'Limit' => $limit]
    //                     );
    //
    //                     if ($records->getReturnedResultsCount() > 0) {
    //
    //                         $Matrix_Unique_ID = $records->last()->get('Matrix_Unique_ID');
    //                         $query = '(MatrixModifiedDT=' . $reb_agent_ModDate . '+),(AgentStatus=A),(OfficeMLSID=KWPT01,KWPT02),(Matrix_Unique_ID=' . $Matrix_Unique_ID . '+)';
    //                         Log::info('Returned Count: ' . $records->getReturnedResultsCount());
    //
    //                         $this->agent_records_handler($records);
    //
    //                         $total = $total - (int)$records->getReturnedResultsCount();
    //                         Log::info('END Agent Import Iteration');
    //
    //                     } else {
    //
    //                         $total = 0;
    //                         Log::info('END Agent Import - NO RETS RESULTS');
    //
    //                     }
    //                 }
    //             }
    //
    //             Log::info('END Agent Import');
    //             Log::info('--------');
    //
    //             update_option('reb_agent_ModDate', get_option('reb_agent_ModDate_temp'), false);
    //             delete_option('reb_agent_ModDate_temp');
    //             $rets->Disconnect();
    //
    //         } else {
    //             Log::info("Login problem");
    //         }
    //
    //     } catch (\Exception $e) {
    //         throw new \RuntimeException($e->getMessage());
    //         Log::info('----START Exception----');
    //         Log::info($e->getMessage());
    //         Log::info('----END Exception----');
    //         update_option('reb_mls_flag', false, false);
    //     }
    //
    // }

    function get_mls_properties($limit = 200)
    {

        global $rets, $wpdb;


        // $reb_property_ModDate = get_option('reb_property_ModDate') ? get_option('reb_property_ModDate') : '2019-11-01T00:00:00';
        $reb_property_ModDate = '2019-11-01T00:00:00';
        $date = date('Y-m-d\TH:i:s', time());

        // $reb_mls_offset_arr = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'reb_mls_offset_%'", ARRAY_A);
        //
        // if (empty($reb_mls_offset_arr)) {
        //
        //     $timestamp = wp_next_scheduled('reb_property_hook');
        //     wp_unschedule_event($timestamp, 'reb_property_hook');
        //     wp_reschedule_event(time(), 'two_min', 'reb_property_hook');
        //
        //     update_option('reb_property_ModDate_temp', $date, false);
        //
        //     Log::info('--------');
        //     Log::info('Start REB MLS Import');
        //     Log::info('Start date: ' . get_option('reb_property_ModDate_temp'));
        //     Log::info('From Date: ' . $reb_property_ModDate);
        //
        // } else {
        //
        //     $timestamp = wp_next_scheduled('reb_property_hook');
        //     wp_unschedule_event($timestamp, 'reb_property_hook');
        //     wp_reschedule_event(time(), 'two_min', 'reb_property_hook');
        //
        //     Log::info('--------');
        //     Log::info('Continue REB MLS Import');
        //     Log::info('Point date: ' . get_option('reb_property_ModDate_temp'));
        //     Log::info('From Date: ' . $reb_property_ModDate);
        // }

        try {

            $buletin = $rets->login();
            if ($buletin) {

                $classes = array();
                if (empty($reb_mls_offset_arr)) {
                    // $classes = $rets->GetClassesMetadata('Property')->toArray();
                    $classes = array(
                        ['ClassName' => 'CrossProperty']
                    );
                } else {
                    foreach ($reb_mls_offset_arr as $offset_class) {
                        $classes[] = array(
                            'ClassName' => str_replace('reb_mls_offset_', '', $offset_class['option_name']),
                            'reb_mls_offset' => $offset_class['option_value']
                        );
                    }
                }

                foreach ($classes as $class) {

                    // if( in_array($class['ClassName'], array('BusinessOpportunity')) ) continue;

                    if (array_key_exists('reb_mls_offset', $class)) {
                        $reb_mls_offset = intval($class['reb_mls_offset']);
                    } else {
                        $reb_mls_offset = 0;
                    }

                    $class = print_r($class['ClassName'], TRUE);
                    Log::info('Class: ' . $class);
                    Log::info('reb_mls_offset:' . $reb_mls_offset);

                    $PropSubTypes = ',(PropertySubType=CONDO,OYO,COOP,DPLX,QUAD,MULT,SFR,TWNHS,TPLX,LND,WPWL,LOFT,STUD,CABIN,APT,MANH,MANL,MOB,BSLP)';
                    $query_part = ',(StandardStatus=U),(StateOrProvince=CA),(CountyOrParish=LA,OR,SD)' . $PropSubTypes;//(City=NB,IR,LA)';//,ANA,LONG,HB,TORR,PAS,SM
                    $query = '(ModificationTimestamp=' . $reb_property_ModDate . '+)' . $query_part;

                    $result = $rets->Search('Property', $class, $query, ['Format' => 'COMPACT-DECODED', 'Select' => 'ListingKeyNumeric', 'Limit' => 1, 'Offset' => $reb_mls_offset]);

                    $total_res = intval($result->getTotalResultsCount());
                    Log::info('total_res: ' . $total_res);

                    if ($total_res > 0) {
                        $Matrix_Unique_ID = $result->first()->get('ListingKeyNumeric');
                        $query = '(ModificationTimestamp=' . $reb_property_ModDate . '+),(ListingKeyNumeric=' . $Matrix_Unique_ID . '+)' . $query_part;

                        $total = $total_res < 500 ? $total_res : 500; //$result->getTotalResultsCount();
                        if ($total > 0) {
                            $saved_records = 0;
                            while ($total > 0) {

                                // if (get_option('reb_mls_abort_flag')) {
                                //     Log::info('reb_mls_abort_flag is ON');
                                //
                                //     $timestamp = wp_next_scheduled('reb_property_hook');
                                //     wp_unschedule_event($timestamp, 'reb_property_hook');
                                //     wp_reschedule_event(time(), 'daily', 'reb_property_hook');
                                //
                                //     return;
                                // }

                                Log::info('Total: ' . $total);
                                $records = $rets->Search(
                                    'Property',
                                    $class,
                                    $query,
                                    ['Format' => 'COMPACT-DECODED', 'Select' => $this->mls_select, 'StandardNames' => 0, 'Limit' => $limit]
                                );

                                $ReturnedCount = intval($records->getReturnedResultsCount());
                                $reb_mls_offset += $ReturnedCount;

                                if ($ReturnedCount > 0) {

                                    $max_Matrix_Unique_ID = $this->property_records_handler($records, $class);

                                    // $Matrix_Unique_ID = $records->last()->get('ListingKeyNumeric');
                                    $query = '(ModificationTimestamp=' . $reb_property_ModDate . '+),(ListingKeyNumeric=' . $max_Matrix_Unique_ID . '+),~(ListingKeyNumeric=' . $max_Matrix_Unique_ID . ')' . $query_part;
                                    Log::info('Returned Count: ' . $records->getReturnedResultsCount());

                                    $total = $total - $ReturnedCount;
                                    Log::info('END Property Import Iteration');
                                    $saved_records += 100;
                                    echo "Saved $saved_records records\n";


                                } else {

                                    $total = 0;
                                    Log::info('END Property Import - NO RETS RESULTS');

                                }
                            }
                        }
                    }

                    // if ($reb_mls_offset < $total_res) {
                    //
                    //     update_option('reb_mls_offset_' . $class, $reb_mls_offset, false);
                    //
                    // } else {
                    //
                    //     delete_option('reb_mls_offset_' . $class);
                    //
                    // }

                } //END foreach $clases

                Log::info('END Point Import');
                Log::info('--------');

                $rets->Disconnect();

            } else {
                Log::info("Login problem");
            }


        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
            Log::info('----START Exception----');
            Log::info($e->getMessage());
            Log::info('----END Exception----');
            // update_option('reb_mls_flag', false, false);
        }

        // $reb_mls_offset_arr = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'reb_mls_offset_%'", ARRAY_A);
        //
        // if (empty($reb_mls_offset_arr)) {
        //
        //     $timestamp = wp_next_scheduled('reb_property_hook');
        //     wp_unschedule_event($timestamp, 'reb_property_hook');
        //     wp_reschedule_event(time(), 'daily', 'reb_property_hook');
        //
        //     update_option('reb_property_ModDate', get_option('reb_property_ModDate_temp'), false);
        //     delete_option('reb_property_ModDate_temp');
        //
        //     Log::info('END Property Import');
        //     Log::info('--------');
        //
        // }
    }

    function get_mls_properties_initial($limit = 200)
    {

        // global $rets, $wpdb;
        global $rets;


        // $reb_property_ModDate = get_option('reb_property_ModDate') ? get_option('reb_property_ModDate') : '2019-11-01T00:00:00';
        $reb_property_ModDate = '1800-11-01T00:00:00';
        $date = date('Y-m-d\TH:i:s', time());

        // $reb_mls_offset_arr = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'reb_mls_offset_%'", ARRAY_A);
        //
        // if (empty($reb_mls_offset_arr)) {
        //
        //     $timestamp = wp_next_scheduled('reb_property_hook');
        //     wp_unschedule_event($timestamp, 'reb_property_hook');
        //     wp_reschedule_event(time(), 'two_min', 'reb_property_hook');
        //
        //     update_option('reb_property_ModDate_temp', $date, false);
        //
        //     Log::info('--------');
        //     Log::info('Start REB MLS Import');
        //     Log::info('Start date: ' . get_option('reb_property_ModDate_temp'));
        //     Log::info('From Date: ' . $reb_property_ModDate);
        //
        // } else {
        //
        //     $timestamp = wp_next_scheduled('reb_property_hook');
        //     wp_unschedule_event($timestamp, 'reb_property_hook');
        //     wp_reschedule_event(time(), 'two_min', 'reb_property_hook');
        //
        //     Log::info('--------');
        //     Log::info('Continue REB MLS Import');
        //     Log::info('Point date: ' . get_option('reb_property_ModDate_temp'));
        //     Log::info('From Date: ' . $reb_property_ModDate);
        // }

        try {

            $buletin = $rets->login();
            if ($buletin) {

                $classes = array();
                if (empty($reb_mls_offset_arr)) {
                    // $classes = $rets->GetClassesMetadata('Property')->toArray();
                    $classes = array(
                        ['ClassName' => 'CrossProperty']
                    );
                } else {
                    foreach ($reb_mls_offset_arr as $offset_class) {
                        $classes[] = array(
                            'ClassName' => str_replace('reb_mls_offset_', '', $offset_class['option_name']),
                            'reb_mls_offset' => $offset_class['option_value']
                        );
                    }
                }

                foreach ($classes as $class) {

                    // if( in_array($class['ClassName'], array('BusinessOpportunity')) ) continue;

                    if (array_key_exists('reb_mls_offset', $class)) {
                        $reb_mls_offset = intval($class['reb_mls_offset']);
                    } else {
                        $reb_mls_offset = 0;
                    }

                    $class = print_r($class['ClassName'], TRUE);
                    Log::info('Class: ' . $class);
                    Log::info('reb_mls_offset:' . $reb_mls_offset);

                    $PropSubTypes = ',(PropertySubType=CONDO,OYO,COOP,DPLX,QUAD,MULT,SFR,TWNHS,TPLX,LND,WPWL,LOFT,STUD,CABIN,APT,MANH,MANL,MOB,BSLP)';
                    $query_part = ',(StandardStatus=A),(StateOrProvince=CA),(CountyOrParish=LA,OR,SD)' . $PropSubTypes;//(City=NB,IR,LA)';//,ANA,LONG,HB,TORR,PAS,SM
                    $query = '(ModificationTimestamp=' . $reb_property_ModDate . '+)' . $query_part;

                    $result = $rets->Search('Property', $class, $query, ['Format' => 'COMPACT-DECODED', 'Select' => 'ListingKeyNumeric', 'Limit' => 1, 'Offset' => $reb_mls_offset]);

                    $total_res = intval($result->getTotalResultsCount());
                    Log::info('total_res: ' . $total_res);

                    if ($total_res > 0) {
                        $Matrix_Unique_ID = $result->first()->get('ListingKeyNumeric');

                            // iterate over all records until nothing left
                            $query = '(ModificationTimestamp=' . $reb_property_ModDate . '+),(ListingKeyNumeric=' . $Matrix_Unique_ID . '+)' . $query_part;


                            // $total = $total_res < 5000 ? $total_res : 5000; //$result->getTotalResultsCount();
                            $total = $total_res; //$result->getTotalResultsCount();
                            $saved_records = 0;

                            if ($total > 0) {
                                while ($total > 0) {
                                    // iterate over limited collection

                                    Log::info('Total: ' . $total);
                                    $records = $rets->Search(
                                        'Property',
                                        $class,
                                        $query,
                                        ['Format' => 'COMPACT-DECODED', 'Select' => RebImport::$mls_list, 'StandardNames' => 0, 'Limit' => $limit]
                                    );

                                    $ReturnedCount = intval($records->getReturnedResultsCount());
                                    $reb_mls_offset += $ReturnedCount;

                                    if ($ReturnedCount > 0) {

                                        $max_Matrix_Unique_ID = $this->property_records_handler($records, $class);

                                        // $Matrix_Unique_ID = $records->last()->get('ListingKeyNumeric');
                                        $query = '(ModificationTimestamp=' . $reb_property_ModDate . '+),(ListingKeyNumeric=' . $max_Matrix_Unique_ID . '+),~(ListingKeyNumeric=' . $max_Matrix_Unique_ID . ')' . $query_part;
                                        Log::info('Returned Count: ' . $records->getReturnedResultsCount());

                                        $total = $total - $ReturnedCount;
                                        Log::info('END Property Import Iteration');
                                        $saved_records += $ReturnedCount;
                                        echo "Saved $saved_records records\n";


                                    } else {

                                        $total = 0;
                                        $total_res -= $saved_records;
                                        Log::info('END Property Import - NO RETS RESULTS');

                                    }
                                }
                            }
                    }

                    // if ($reb_mls_offset < $total_res) {
                    //
                    //     update_option('reb_mls_offset_' . $class, $reb_mls_offset, false);
                    //
                    // } else {
                    //
                    //     delete_option('reb_mls_offset_' . $class);
                    //
                    // }

                } //END foreach $clases

                Log::info('END Point Import');
                Log::info('--------');

                $rets->Disconnect();

            } else {
                Log::info("Login problem");
            }


        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
            Log::info('----START Exception----');
            Log::info($e->getMessage());
            Log::info('----END Exception----');
            // update_option('reb_mls_flag', false, false);
        }

        // $reb_mls_offset_arr = $wpdb->get_results("SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'reb_mls_offset_%'", ARRAY_A);
        //
        // if (empty($reb_mls_offset_arr)) {
        //
        //     $timestamp = wp_next_scheduled('reb_property_hook');
        //     wp_unschedule_event($timestamp, 'reb_property_hook');
        //     wp_reschedule_event(time(), 'daily', 'reb_property_hook');
        //
        //     update_option('reb_property_ModDate', get_option('reb_property_ModDate_temp'), false);
        //     delete_option('reb_property_ModDate_temp');
        //
        //     Log::info('END Property Import');
        //     Log::info('--------');
        //
        // }
    }

//     function get_mls_properties_test($limit = 200)
//     {
//
//         global $rets, $wpdb;
//
//
//         $reb_property_ModDate = get_option('reb_property_ModDate') ? get_option('reb_property_ModDate') : '1800-11-01T00:00:00';
//         $date = date('Y-m-d\TH:i:s', time());
//
//         // $reb_mls_offset_arr = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'reb_mls_offset_%'", ARRAY_A );
//         $reb_mls_offset_arr = NULL;
//
//         if (empty($reb_mls_offset_arr)) {
//
//             $timestamp = wp_next_scheduled('reb_property_hook');
//             wp_unschedule_event($timestamp, 'reb_property_hook');
//             wp_reschedule_event(time(), 'two_min', 'reb_property_hook');
//
//             update_option('reb_property_ModDate_temp', $date, false);
//
//             Log::info('--------');
//             Log::info('Start REB MLS Import');
//             Log::info('Start date: ' . get_option('reb_property_ModDate_temp'));
//             Log::info('From Date: ' . $reb_property_ModDate);
//
//         } else {
//
//             $timestamp = wp_next_scheduled('reb_property_hook');
//             wp_unschedule_event($timestamp, 'reb_property_hook');
//             wp_reschedule_event(time(), 'two_min', 'reb_property_hook');
//
//             Log::info('--------');
//             Log::info('Continue REB MLS Import');
//             Log::info('Point date: ' . get_option('reb_property_ModDate_temp'));
//             Log::info('From Date: ' . $reb_property_ModDate);
//         }
//
//         try {
//
//             $buletin = $rets->login();
//             if ($buletin) {
//
//                 $classes = array();
//                 if (empty($reb_mls_offset_arr)) {
//                     // $classes = $rets->GetClassesMetadata('Property')->toArray();
//                     $classes = array(
//                         ['ClassName' => 'CrossProperty'],
//                     );
//                 } else {
//                     foreach ($reb_mls_offset_arr as $offset_class) {
//                         $classes[] = array(
//                             'ClassName' => str_replace('reb_mls_offset_', '', $offset_class['option_name']),
//                             'reb_mls_offset' => $offset_class['option_value']
//                         );
//                     }
//                 }
//
//                 foreach ($classes as $class) {
//
//                     // if( in_array($class['ClassName'], array('BusinessOpportunity')) ) continue;
//
//                     if (array_key_exists('reb_mls_offset', $class)) {
//                         $reb_mls_offset = intval($class['reb_mls_offset']);
//                     } else {
//                         $reb_mls_offset = 0;
//                     }
//
//                     $class = print_r($class['ClassName'], TRUE);
//                     Log::info('Class: ' . $class);
//                     Log::info('reb_mls_offset:' . $reb_mls_offset);
//
//                     $PropSubTypes = ',(PropertySubType=CONDO,OYO,COOP,DPLX,QUAD,MULT,SFR,TWNHS,TPLX,LND,WPWL,LOFT,STUD,CABIN,APT,MANH,MANL,MOB,BSLP)';
//                     Log::info('StandardStatus=W,X,Z,I');
//                     $query_part = ',(StandardStatus=W,X,Z,I),(StateOrProvince=CA),(CountyOrParish=LA,OR,SD)' . $PropSubTypes;//(City=NB,IR,LA)';//,ANA,LONG,HB,TORR,PAS,SM
//                     $query = '(ModificationTimestamp=' . $reb_property_ModDate . '+)' . $query_part;
//
//                     $result = $rets->Search('Property', $class, $query, ['Format' => 'COMPACT-DECODED', 'Select' => 'ListingKeyNumeric', 'Limit' => 1, 'Offset' => $reb_mls_offset]);
//
//                     $total_res = intval($result->getTotalResultsCount());
//                     Log::info('total_res: ' . $total_res);
//
//                     if ($total_res > 0) {
//                         $Matrix_Unique_ID = $result->first()->get('ListingKeyNumeric');
//                         $query = '(ModificationTimestamp=' . $reb_property_ModDate . '+),(ListingKeyNumeric=' . $Matrix_Unique_ID . '+)' . $query_part;
//
//                         $total = $total_res < 500 ? $total_res : 500; //$result->getTotalResultsCount();
//                         if ($total > 0) {
//                             while ($total > 0) {
//
//                                 if (get_option('reb_mls_abort_flag')) {
//                                     Log::info('reb_mls_abort_flag is ON');
//
//                                     $timestamp = wp_next_scheduled('reb_property_hook');
//                                     wp_unschedule_event($timestamp, 'reb_property_hook');
//                                     wp_reschedule_event(time(), 'daily', 'reb_property_hook');
//
//                                     return;
//                                 }
//
//                                 Log::info('Total: ' . $total);
//                                 $records = $rets->Search(
//                                     'Property',
//                                     $class,
//                                     $query,
//                                     ['Format' => 'COMPACT-DECODED', 'Select' => $this->mls_select, 'StandardNames' => 0, 'Limit' => $limit]
//                                 );
//
//                                 $ReturnedCount = intval($records->getReturnedResultsCount());
//                                 $reb_mls_offset += $ReturnedCount;
//
//                                 if ($ReturnedCount > 0) {
//
//                                     Log::info('Modify!!! $this->property_records_handler');
//                                     $max_Matrix_Unique_ID = $this->property_records_handler($records, $class);
//                                     $max_Matrix_Unique_ID = $this->property_records_handler_test($records, $class);
//
//                                     // $Matrix_Unique_ID = $records->last()->get('ListingKeyNumeric');
// //                                    $query = '(ModificationTimestamp='.$reb_property_ModDate.'+),(ListingKeyNumeric='.$max_Matrix_Unique_ID.'+),~(ListingKeyNumeric='.$max_Matrix_Unique_ID.')'.$query_part;
//                                     Log::info('Returned Count: ' . $records->getReturnedResultsCount());
//
//                                     $total = $total - $ReturnedCount;
//                                     Log::info('END Property Import Iteration');
//
//                                 } else {
//
//                                     $total = 0;
//                                     Log::info('END Property Import - NO RETS RESULTS');
//
//                                 }
//                             }
//                         }
//                     }
//
//                     if ($reb_mls_offset < $total_res) {
//
//                         update_option('reb_mls_offset_' . $class, $reb_mls_offset, false);
//
//                     } else {
//
//                         delete_option('reb_mls_offset_' . $class);
//
//                     }
//
//                 } //END foreach $clases
//
//                 Log::info('END Point Import');
//                 Log::info('--------');
//
//                 $rets->Disconnect();
//
//             } else {
//                 Log::info("Login problem");
//             }
//
//
//         } catch (\Exception $e) {
//             throw new \RuntimeException($e->getMessage());
//             Log::info('----START Exception----');
//             Log::info($e->getMessage());
//             Log::info('----END Exception----');
//             update_option('reb_mls_flag', false, false);
//         }
//
// //        $reb_mls_offset_arr = $wpdb->get_results( "SELECT option_name, option_value FROM $wpdb->options WHERE option_name LIKE 'reb_mls_offset_%'", ARRAY_A );
//
// //        if ( empty($reb_mls_offset_arr) ) {
// //
// //            $timestamp = wp_next_scheduled('reb_property_hook');
// //            wp_unschedule_event( $timestamp, 'reb_property_hook');
// //            wp_reschedule_event( time(), 'daily', 'reb_property_hook');
// //
// //            update_option('reb_property_ModDate', get_option('reb_property_ModDate_temp'), false);
// //            delete_option('reb_property_ModDate_temp');
// //
// //            Log::info('END Property Import');
// //            Log::info('--------');
// //
// //        }
//     }
//
//     function get_mls_openhouse($limit = 200)
//     {
//         global $rets;
//
//         $Select = 'matrix_unique_id,MatrixModifiedDT,Listing_MUI,OpenHouseDate,StartTime,EndTime';
//
//         $reb_OpenHouse_ModDate = get_option('reb_OpenHouse_ModDate') ? get_option('reb_OpenHouse_ModDate') : '2019-01-01T00:00:00';
//         $date = date('Y-m-d\TH:i:s', time());
//
//         update_option('reb_OpenHouse_ModDate_temp', $date, false);
//
//         Log::info('--------');
//         Log::info('Start MLS OpenHouse Import');
//         Log::info('Start date: ' . get_option('reb_OpenHouse_ModDate_temp'));
//         Log::info('From Date: ' . $reb_OpenHouse_ModDate);
//
//         try {
//
//             $query = '(MatrixModifiedDT=' . $reb_OpenHouse_ModDate . '+),(OpenHouseDate=' . $date . '+),(ActiveYN=1),(OpenHouseType=OfficeOnly,Public)';
//
//             $buletin = $rets->login();
//             if ($buletin) {
//
//                 $result = $rets->Search('OpenHouse', 'OpenHouse', $query, ['Format' => 'COMPACT-DECODED', 'Select' => 'matrix_unique_id', 'Limit' => 1, 'Offset' => 0]);
//                 $total = $result->getTotalResultsCount();
//
//                 if ($total > 0) {
//                     while ($total > 0) {
//
//                         if (get_option('reb_mls_abort_flag')) return;
//
//                         Log::info('Total: ' . $total);
//                         $records = $rets->Search(
//                             'OpenHouse',
//                             'OpenHouse',
//                             $query,
//                             ['Format' => 'COMPACT-DECODED', 'Select' => $Select, 'StandardNames' => 0, 'Limit' => $limit]
//                         );
//
//                         if ($records->getReturnedResultsCount() > 0) {
//
//                             $Matrix_Unique_ID = $records->last()->get('matrix_unique_id');
//                             $query = '(MatrixModifiedDT=' . $reb_OpenHouse_ModDate . '+),(OpenHouseDate=' . $date . '+),(ActiveYN=1),(OpenHouseType=OfficeOnly,Public),(matrix_unique_id=' . $Matrix_Unique_ID . '+)';
//                             Log::info('Returned Count: ' . $records->getReturnedResultsCount());
//
//                             $this->openhouse_records_handler($records);
//
//                             $total = $total - (int)$records->getReturnedResultsCount();
//                             Log::info('END OpenHouse Import Iteration');
//
//                         } else {
//
//                             $total = 0;
//                             Log::info('END OpenHouse Import - NO RETS RESULTS');
//
//                         }
//                     }
//                 }
//
//                 Log::info('END OpenHouse Import');
//                 Log::info('--------');
//
//                 update_option('reb_OpenHouse_ModDate', get_option('reb_OpenHouse_ModDate_temp'), false);
//                 delete_option('reb_OpenHouse_ModDate_temp');
//                 $rets->Disconnect();
//
//             } else {
//                 Log::info("Login problem");
//             }
//
//         } catch (\Exception $e) {
//             throw new \RuntimeException($e->getMessage());
//             Log::info('----START Exception----');
//             Log::info($e->getMessage());
//             Log::info('----END Exception----');
//             update_option('reb_mls_flag', false, false);
//         }
//
//
//     }
//
    function property_records_handler($records, $class)
    {

        // global $wpdb;
        // $wp_ = $wpdb->prefix;
        $iterat = 1;
        $max_Matrix_Unique_ID = 0;

        foreach ($records as $record) {
            // Log::info('Record iterat: ' . $iterat);

            $Matrix_Unique_ID = $record->get('ListingKeyNumeric');
            $record_exists = RebMlsListing::where('mls->Matrix_Unique_ID', $Matrix_Unique_ID)->count() > 0;
            if($record_exists) {
                echo "Skip... Record exists\n";
                continue;
            }

            $max_Matrix_Unique_ID = $Matrix_Unique_ID > $max_Matrix_Unique_ID ? $Matrix_Unique_ID : $max_Matrix_Unique_ID;

            RebImport::update_property_fields($post_id = NULL, $record, $class);
            // RebMlsListing::update_property_index($post_id = NULL, $record, $class);

            // $query = $wpdb->prepare("SELECT post_id FROM {$wp_}reb_mls_listing WHERE JSON_VALID(mls) AND JSON_UNQUOTE(JSON_EXTRACT(mls, \"$.Matrix_Unique_ID\")) = '%s'", $Matrix_Unique_ID);
            // $post_id = $wpdb->get_var($query);
            // if ($post_id !== NULL) {
            //
            //     REB_mls::update_property_fields($post_id, $record, $class);
            //     REB_mls::update_property_index($post_id, $record, $class);
            //     Log::info('update_property_fields: ' . $post_id);
            //
            // } else {

            // $post_data = array(
            //     'post_title' => '',
            //     'post_content' => '',
            //     'post_type' => 'reb_property',
            //     'post_status' => 'publish',
            //     'post_author' => 1,
            // );
            // $post_id = wp_insert_post($post_data, true);
            //
            // if (is_wp_error($post_id)) {
            //     Log::info('Some error: ' . $post_id->get_error_message());
            // } else {
            //     REB_mls::update_property_fields($post_id, $record, $class);
            //     REB_mls::update_property_index($post_id, $record, $class);
            //     Log::info('create_property & fields: ' . $post_id);
            // }

            // }
            // usleep(100000);
            echo "Property $iterat updated\n";
            $iterat++;
        }
        return $max_Matrix_Unique_ID;

    }
//
//     function property_records_handler_test($records, $class)
//     {
//
//         // global $wpdb;
//         // $wp_ = $wpdb->prefix;
//         // $iterat = 1;
//         // $max_Matrix_Unique_ID = 0;
//
//         foreach ($records as $record) {
//             // Log::info('update_property_fields after creating new post: ' . $post_id);
//             $record_json = json_decode($record, true);
//             if ($record_json['StandardStatus'] == 'Expired' || $record_json['StandardStatus'] == 'Delete') {
//                 Log::info('StandardStatus is Expired or Delete ');
//                 Log::info('$record: ');
//                 Log::info($record);
//                 Log::info('$class: ');
//                 Log::info($class);
//             }
//
//             // Log::info('Record iterat: ' . $iterat);
//
//             // $Matrix_Unique_ID = $record->get('ListingKeyNumeric');
//             //
//             // $max_Matrix_Unique_ID = $Matrix_Unique_ID > $max_Matrix_Unique_ID ? $Matrix_Unique_ID : $max_Matrix_Unique_ID;
//             //
//             // $query = $wpdb->prepare("SELECT post_id FROM {$wp_}reb_mls_listing WHERE JSON_VALID(mls) AND JSON_UNQUOTE(JSON_EXTRACT(mls, \"$.Matrix_Unique_ID\")) = '%s'", $Matrix_Unique_ID);
//             // $post_id = $wpdb->get_var($query);
//             // if ( $post_id !== NULL ) {
//             //
//             //     // REB_mls::update_property_fields($post_id, $record, $class);
//             //     // REB_mls::update_property_index($post_id, $record, $class);
//             //     Log::info('update_property_fields: ' . $post_id);
//             //     $record_json = json_decode($record, true);
//             //     if( $record_json['StandardStatus'] == 'Expired' || $record_json['StandardStatus'] == 'Delete') {
//             //         Log::info('StandardStatus is Expired or Delete ');
//             //         Log::info('$record: ');
//             //         Log::info($record);
//             //         Log::info('$class: ');
//             //         Log::info($class);
//             //     }
//             //
//             // } else {
//             //
//             //     // $post_data = array(
//             //     //     'post_title'    => '',
//             //     //     'post_content'  => '',
//             //     //     'post_type'     => 'reb_property',
//             //     //     'post_status'   => 'publish',
//             //     //     'post_author'   => 1,
//             //     // );
//             //     // $post_id = wp_insert_post($post_data, true);
//             //     Log::info('Creating new wp post: ');
//             //
//             //     if( is_wp_error($post_id) ){
//             //         Log::info('Some error: ' . $post_id->get_error_message());
//             //     }
//             //     else {
//             //         Log::info('update_property_fields after creating new post: ' . $post_id);
//             //         $record_json = json_decode($record, true);
//             //         if( $record_json['StandardStatus'] == 'Expired' || $record_json['StandardStatus'] == 'Delete') {
//             //             Log::info('StandardStatus is Expired or Delete ');
//             //             Log::info('$record: ');
//             //             Log::info($record);
//             //             Log::info('$class: ');
//             //             Log::info($class);
//             //         }
//             //         // REB_mls::update_property_fields($post_id, $record, $class);
//             //         // REB_mls::update_property_index($post_id, $record, $class);
//             //         Log::info('create_property & fields: ' . $post_id);
//             //
//             //
//             //     }
//             //
//             // }
//             usleep(100000);
//             // $iterat++;
//         }
//         // return $max_Matrix_Unique_ID;
//         return 'nothing lol';
//
//     }
//
//
//     function agent_records_handler($records)
//     {
//
//         global $wpdb;
//         $wp_ = $wpdb->prefix;
//         $iterat = 1;
//
//         foreach ($records as $record) {
//             Log::info('Record iterat: ' . $iterat);
//
//             $Matrix_Unique_ID = $record->get('Matrix_Unique_ID');
//
//             $query = $wpdb->prepare("SELECT post_id FROM {$wp_}reb_mls_agents WHERE JSON_VALID(mls) AND JSON_UNQUOTE(JSON_EXTRACT(mls, \"$.Matrix_Unique_ID\")) = '%s'", $Matrix_Unique_ID);
//             $post_id = $wpdb->get_var($query);
//             if ($post_id !== NULL) {
//
//                 REB_mls::update_agent_fields($post_id, $record);
//                 Log::info('update_agent_fields: ' . $post_id);
//
//             } else {
//
//                 $post_data = array(
//                     'post_title' => '',
//                     'post_content' => '',
//                     'post_type' => 'reb_agent',
//                     'post_status' => 'publish',
//                     'post_author' => 1,
//                 );
//                 $post_id = wp_insert_post($post_data, true);
//
//                 if (is_wp_error($post_id)) {
//                     Log::info('Some error: ' . $post_id->get_error_message());
//                 } else {
//                     REB_mls::update_agent_fields($post_id, $record);
//                     Log::info('create_agent & fields: ' . $post_id);
//                 }
//
//             }
//             usleep(100000);
//             $iterat++;
//
//         }
//
//     }
//
//     function openhouse_records_handler($records)
//     {
//
//         global $wpdb;
//         $wp_ = $wpdb->prefix;
//         $iterat = 1;
//
//         foreach ($records as $record) {
//             Log::info('Record iterat: ' . $iterat);
//
//             $Listing_MUI = $record->get('Listing_MUI');
//             $query = $wpdb->prepare("SELECT post_id FROM {$wp_}reb_mls_listing WHERE JSON_VALID(mls) AND JSON_UNQUOTE(JSON_EXTRACT(mls, \"$.Matrix_Unique_ID\")) = '%s' AND JSON_UNQUOTE(JSON_EXTRACT(mls, \"$.ListOffice_MUI\")) IN ('1035946','77945024')", $Listing_MUI);
//             $post_id = $wpdb->get_var($query);
//             if ($post_id !== NULL) {
//
//                 REB_mls::update_openhouse_fields($post_id, $record);
//                 Log::info('update_openhouse_fields: ' . $post_id);
//
//             } else {
//
//                 //check why we haven't it
//
//             }
//             usleep(100000);
//             $iterat++;
//
//         }
//
//     }

    function update_property_fields($post_id, $record, $class)
    {

        // global $wpdb;
        global $rets;
        $params = [];

        /* MLS */
        $MLSNumber = $record->get('ListingId') ? trim($record->get('ListingId')) : '';
        $Matrix_Unique_ID = $record->get('ListingKeyNumeric') ? trim($record->get('ListingKeyNumeric')) : '';
        $MatrixModifiedDT = $record->get('ModificationTimestamp') ? trim($record->get('ModificationTimestamp')) : '';
        $ListOffice_MUI = $record->get('ListOfficeMlsId') ? trim($record->get('ListOfficeMlsId')) : '';
        $ListAgent_MUI = $record->get('ListAgentMlsId') ? trim($record->get('ListAgentMlsId')) : '';
        $ListAgentFirstName = $record->get('ListAgentFirstName') ? trim($record->get('ListAgentFirstName')) : '';
        $ListAgentLastName = $record->get('ListAgentLastName') ? trim($record->get('ListAgentLastName')) : '';
        $ListOfficeName = $record->get('ListOfficeName') ? trim($record->get('ListOfficeName')) : '';

        $mls_prop_status = $record->get('StandardStatus');
        $mls_arr = array(
            'Matrix_Unique_ID' => $Matrix_Unique_ID,
            'MLSupdate' => $MatrixModifiedDT,
            'MLSClass' => $class,
            'MLSNumber' => $MLSNumber,
            'Status' => $mls_prop_status,
            'ListOffice_MUI' => $ListOffice_MUI,
            'ListOfficeName' => $ListOfficeName,
            'ListAgent_MUI' => $ListAgent_MUI,
            'ListAgentFirstName' => $ListAgentFirstName,
            'ListAgentLastName' => $ListAgentLastName
        );
        // update_field('mls', json_encode($mls_arr, JSON_HEX_QUOT), $post_id);
        $params['mls'] = json_encode($mls_arr, JSON_HEX_QUOT);

        /* Price */
        $ListPrice = $record->get('ListPrice') ? $record->get('ListPrice') : '';
        // update_field('price', $ListPrice, $post_id);
        $params['price'] = $ListPrice ?: null;

        /* Address */
        $Country = $record->get('Country') ? trim($record->get('Country')) : '';
        $State = $record->get('StateOrProvince') ? trim($record->get('StateOrProvince')) : '';
        $City = $record->get('City') ? trim($record->get('City')) : '';
        $PostalCode = $record->get('PostalCode') ? trim($record->get('PostalCode')) : '';
        $StreetNumberNumeric = $record->get('StreetNumberNumeric') ? trim($record->get('StreetNumberNumeric')) : '';
        $StreetNumber = $record->get('StreetNumber') ? $StreetNumberNumeric . ' ' . trim($record->get('StreetNumber')) : $StreetNumberNumeric;
        $StreetDirPrefix = $record->get('StreetDirPrefix') ? trim($record->get('StreetDirPrefix')) : '';
        $StreetName = $record->get('StreetName') ? ' ' . trim($record->get('StreetName')) : '';
        $StreetSuffix = $record->get('StreetSuffix') ? ' ' . trim($record->get('StreetSuffix')) : '';
        $StreetDirSuffix = $record->get('StreetDirSuffix') ? trim($record->get('StreetDirSuffix')) : '';
        $UnitNumber = $record->get('UnitNumber') ? ' ' . trim($record->get('UnitNumber')) : '';
        $CountyOrParish = $record->get('CountyOrParish') ? trim($record->get('CountyOrParish')) : '';
        $GeoMarketArea = $record->get('MLSAreaMajor') ? trim($record->get('MLSAreaMajor')) : '';

        $address_arr = array(
            'Country' => $Country,
            'StateOrProvince' => $State,
            'City' => $City,
            'Zip' => $PostalCode,
            'StreetNumber' => $StreetNumber,
            'StreetDirPrefix' => $StreetDirPrefix,
            'StreetName' => $StreetName,
            'StreetSuffix' => $StreetSuffix,
            'StreetDirSuffix' => $StreetDirSuffix,
            'UnitNumber' => $UnitNumber,
            'CountyOrParish' => $CountyOrParish,
            'GeoMarketArea' => $GeoMarketArea
        );
        // update_field('address', json_encode($address_arr, JSON_HEX_QUOT), $post_id);
        $params['address'] = json_encode($address_arr, JSON_HEX_QUOT);

        /* Location */
        $Latitude = $record->get('Latitude') ? trim($record->get('Latitude')) : '';
        $Longitude = $record->get('Longitude') ? trim($record->get('Longitude')) : '';
        $location_arr = array(
            'Latitude' => $Latitude,
            'Longitude' => $Longitude
        );
        // update_field('Location', json_encode($location_arr, JSON_HEX_QUOT), $post_id);
        $params['location'] = json_encode($location_arr, JSON_HEX_QUOT);
        // update_field('Latitude', $Latitude, $post_id);
        // update_field('Longitude', $Longitude, $post_id);
        $params['latitude'] = $Latitude;
        $params['longitude'] = $Longitude;

        /* General */
        $PropertyType = $record->get('PropertyType') ? trim($record->get('PropertyType')) : '';
        $PropertySubType = $record->get('PropertySubType') ? trim($record->get('PropertySubType')) : '';
        $AssetClass = $record->get('AssetClass') ? trim($record->get('AssetClass')) : '';

        // Log::info('PropertySubType: '. $PropertyType);
        if (empty($PropertySubType)) {
            switch ($class) {
                case 'ManufacturedInPark':
                    $PropertySubType = 'Manufactured';
                    break;
                case 'Land':
                    $PropertySubType = 'Land';
                    break;
                case 'CrossProperty':
                    $PropertySubType = !empty($PropertyType) ? $PropertyType : '';
                    break;
                case 'CommercialSale':
                case 'CommercialLease':
                    $PropertySubType = 'Commercial';
                    break;
                default :
                    $PropertySubType = 'unknown';
                    break;
            }
        }
        // Log::info('PropertyType: '. $PropertyType);
        // Log::info('Class 2: '. $class);
        switch ($class) {
            case 'CommercialLease':
            case 'ResidentialLease':
                $list_type = 'Lease';
                break;
            case 'CrossProperty':
                switch ($PropertyType) {
                    case 'Residential Lease':
                    case 'Commercial Lease':
                        $list_type = 'Lease';
                        break;
                    default :
                        $list_type = 'Sell';
                        break;
                }
                break;
            default :
                $list_type = 'Sell';
                break;
        }
        // Log::info('list_type: '. $list_type);

        $BedsTotal = $record->get('BedroomsTotal') ? trim($record->get('BedroomsTotal')) : '';
        $BathsFull = $record->get('BathroomsFull') ? trim($record->get('BathroomsFull')) : '';
        $BathsHalf = $record->get('BathroomsHalf') ? trim($record->get('BathroomsHalf')) : '';
        $NoOfGarageCap = $record->get('GarageSpaces') ? trim($record->get('GarageSpaces')) : 'N/A';
        // $garages = $record->get('GarageDesc') ? trim( $record->get('GarageDesc') ) : '';
        $Style = $record->get('ArchitecturalStyle') ? trim($record->get('ArchitecturalStyle')) : '';
        $year_built = $record->get('YearBuilt') ? trim($record->get('YearBuilt')) : '';
        // $SqFtTotal = $record->get('LotSizeSquareFeet') ? trim( $record->get('LotSizeSquareFeet') ) : '';
        $LotSize = $record->get('LotSizeSquareFeet') ? trim($record->get('LotSizeSquareFeet')) : '';
        $LivingArea = $record->get('LivingArea') ? trim($record->get('LivingArea')) : '';

        $general_arr = array(
            'ListType' => $list_type,
            'PropertyType' => $PropertyType,
            'PropertySubType' => $PropertySubType,
            'AssetClass' => $AssetClass,
            'Bedrooms' => $BedsTotal,
            'Baths' => $BathsFull,
            'BathsHalf' => $BathsHalf,
            'NoOfGarageCap' => $NoOfGarageCap,
            // 'GarageDesc'     => $garages,
            'Style' => $Style,
            'YearBuilt' => $year_built,
            'LivingArea' => $LivingArea,
            'LotSize' => $LotSize
        );
        // update_field('general', json_encode($general_arr, JSON_HEX_QUOT), $post_id);
        $params['general'] = json_encode($general_arr, JSON_HEX_QUOT);


        $PublicRemarks = $record->get('PublicRemarks') ? trim($record->get('PublicRemarks')) : '';
        // update_field('overview', $PublicRemarks, $post_id);
        $params['overview'] = $PublicRemarks;


        /* Aditional */
        $Flooring = $record->get('Flooring') ? trim($record->get('Flooring')) : '';
        $Fireplace = $record->get('FireplaceYN') ? 1 : 0;
        $Cooling = $record->get('CoolingYN') ? 1 : 0;
        $Heating = $record->get('HeatingYN') ? 1 : 0;

        $Appliances = $record->get('Appliances');
        $Dishwasher = strpos($Appliances, 'Dishwasher') !== false ? '1' : '0';
        $Microwave = strpos($Appliances, 'Microwave') !== false ? '1' : '0';
        $Disposal = strpos($Appliances, 'Disposal') !== false ? '1' : '0';
        $IceMaker = strpos($Appliances, 'Ice Maker') !== false ? '1' : '0';

        $InteriorFeatures = $record->get('InteriorFeatures') ? trim($record->get('InteriorFeatures')) : '';

        $Roof = $record->get('Roof') ? trim($record->get('Roof')) : '';
        $Foundation = $record->get('FoundationDetails ') ? trim($record->get('FoundationDetails ')) : '';

        $ExteriorFeatures = $record->get('ExteriorFeatures') ? trim($record->get('ExteriorFeatures')) : '';

        // $FireplacesNumber = $record->get('FireplacesNumber') > 0 ? trim( $record->get('FireplacesNumber') ) : 'no';
        // $Fireplace = $record->get('FireplaceDescription')  ? $FireplacesNumber.'/'.trim( $record->get('FireplaceDescription') ) : $FireplacesNumber;
        // $HeatSystem = $record->get('HeatSystem') ? trim( $record->get('HeatSystem') ) : '';
        // $Floars = $record->get('Floors') ? trim( $record->get('Floors') ) : '';
        // $Roof = $record->get('Roof') ? trim( $record->get('Roof') ) : '';
        // $Exterior = $record->get('Exterior') ? trim( $record->get('Exterior') ) : '';
        // $Disposal = $record->get('Disposal') ? trim( $record->get('Disposal') ) : '';
        // $CoolSystem = $record->get('CoolSystem') ? trim( $record->get('CoolSystem') ) : '';
        // $Icemaker = $record->get('Icemaker') ? 'yes' : 'no';
        // $Foundation = $record->get('Foundation') ? trim( $record->get('Foundation') ) : '';
        // $Interior = $record->get('Interior') ? trim( $record->get('Interior') ) : '';

        // $PublicRemarks = $record->get('PublicRemarks') ? trim( $record->get('PublicRemarks') ) : '';
        // $Legal = $record->get('Legal') ? trim( $record->get('Legal') ) : '';

        $aditional_arr = array(
            'Fireplace' => $Fireplace,
            'Cooling' => $Cooling,
            'Heating' => $Heating,
            'Flooring' => $Flooring,
            'Dishwasher' => $Dishwasher,
            'Microwave' => $Microwave,
            'Disposal' => $Disposal,
            'IceMaker' => $IceMaker,
            'Roof' => $Roof,
            'Foundation' => $Foundation,
            'InteriorFeatures' => $InteriorFeatures,
            'ExteriorFeatures' => $ExteriorFeatures
        );
        // update_field('aditional', json_encode($aditional_arr, JSON_HEX_QUOT), $post_id);
        $params['aditional'] = json_encode($aditional_arr, JSON_HEX_QUOT);

        /* Schools */
        $SchoolElementary = $record->get('ElementarySchool') ? trim($record->get('ElementarySchool')) : '';
        $SchoolMiddle = $record->get('MiddleOrJuniorSchool') ? trim($record->get('MiddleOrJuniorSchool')) : '';
        $SchoolHigh = $record->get('HighSchool') ? trim($record->get('HighSchool')) : '';
        $HighSchoolDistrict = $record->get('HighSchoolDistrict') ? trim($record->get('HighSchoolDistrict')) : '';
        $Schools_arr = array(
            'SchoolElementary' => $SchoolElementary,
            'SchoolMiddle' => $SchoolMiddle,
            'SchoolHigh' => $SchoolHigh,
            'HighSchoolDistrict' => $HighSchoolDistrict
        );
        // update_field('schools', json_encode($Schools_arr, JSON_HEX_QUOT), $post_id);
        $params['schools'] = json_encode($Schools_arr, JSON_HEX_QUOT);

        /* Thumbnail */
        // $objects = $rets->GetObject('Property', 'Photo', $record->get('ListingKeyNumeric'), '0', 1);
        // if( !empty($objects) ) {
        //     update_field('thumbnail', $objects->first()->getLocation(), $post_id);
        // }

        /* Images */
        if ((int)$record->get('PhotosCount') > 0) {
            $query = '(ResourceRecordKeyNumeric=' . $record->get('ListingKeyNumeric') . ')';
            $photoUrls = $rets->Search("Media", "Media", $query, ['Format' => 'COMPACT-DECODED', 'Select' => 'MediaURL,Order', 'StandardNames' => 0]);
            $photo_arr = $photoUrls->toArray();
            usort($photo_arr, function ($a, $b) {
                return $a['Order'] <=> $b['Order'];
            });
            $images = array();
            if (!empty($photo_arr)) {
                foreach ($photo_arr as $object) {
                    // if ($index < 1) continue;
                    if (preg_match('/\.(jpeg|jpg|png|gif)$/i', $object['MediaURL'])) {
                        $images[] = $object['MediaURL'];
                    }
                }

                // Log::info( print_r($images, true) );
                // update_field('thumbnail', $images[0], $post_id);
                $params['thumbnail'] = $images[0];
                // update_field('images', json_encode($images), $post_id);
                $params['images'] = json_encode($images);
            }


            // $thumb_key = array_search(0, array_column($photo_arr, 'Order'));
            // Log::info( '$thumb_key: '. $thumb_key );
            // if( $thumb_key ) {
            //     update_field('thumbnail', $photo_arr[$thumb_key]['Order'], $post_id);
            // }
        }


        // $objects = $rets->GetObject('Property', 'LargePhoto', $record->get('ListingKeyNumeric'), '*', 1);
        // $images = array();
        // if( !empty($objects) ) {
        //     foreach ($objects as $index => $object) {
        //         if ($index < 1) continue;
        //         $images[] = $object->getLocation();
        //     }
        // }
        // update_field('images', json_encode($images), $post_id);


        /* SOME ELSE
        //Sale or Rent
        $Listing_type = $record->get('ForSale') ? 9 : ($record->get('ForLease') ? 8 : ($record->get('PropertyType') == 'Rental' ? 8 : 9));

        //Agent + Office

        $office_mui = $record->get('ListOffice_MUI') ? trim($record->get('ListOffice_MUI')) : '';

        $SellingOffice_MUI = $record->get('SellingOffice_MUI') ? $record->get('SellingOffice_MUI') : '';
        $SellingOfficeMLSID = $record->get('SellingOfficeMLSID') ? $record->get('SellingOfficeMLSID') : '';
        $legal_description = $record->get('Legal') ? $record->get('Legal') : '';
        */

        // // Update post title and permalink (post_name)
        // $short_state = RebMlsListing::format_state($State, 'abbr');
        // $title_arr = array(
        //     $StreetNumber,
        //     $StreetDirPrefix,
        //     $StreetName,
        //     $StreetSuffix,
        //     $StreetDirSuffix,
        //     $UnitNumber,
        //     $City,
        //     $short_state,
        //     $PostalCode
        // );
        // foreach ($title_arr as $key => $value)
        //     if (empty(trim($value)))
        //         unset($title_arr[$key]);
        //
        // $title = implode(' ', $title_arr);
        // $post_name = implode('-', $title_arr);
        //
        // $post_data = array(
        //     'ID' => $post_id,
        //     'post_title' => wp_strip_all_tags($title),
        //     'post_name' => $post_name
        // );
        // wp_update_post($post_data);
        //
        // Log::info('prop: ' . $title);
        // Log::info('prop_link: ' . get_permalink($post_id));
        RebMlsListing::create($params);
    }

    // function update_agent_fields($post_id, $record)
    // {
    //
    //     // global $wpdb;
    //     global $rets;
    //
    //     /* MLS */
    //     $Matrix_Unique_ID = $record->get('Matrix_Unique_ID') ? trim($record->get('Matrix_Unique_ID')) : '';
    //     $MatrixModifiedDT = $record->get('MatrixModifiedDT') ? trim($record->get('MatrixModifiedDT')) : '';
    //     $Office_MUI = $record->get('Office_MUI') ? trim($record->get('Office_MUI')) : '';
    //     $AgentStatus = $record->get('AgentStatus') ? trim($record->get('AgentStatus')) : '';
    //
    //     $mls_arr = array(
    //         'Matrix_Unique_ID' => $Matrix_Unique_ID,
    //         'Status' => $AgentStatus,
    //         'MLSupdate' => $MatrixModifiedDT,
    //         'Office_MUI' => $Office_MUI
    //     );
    //     update_field('mls', json_encode($mls_arr, JSON_HEX_QUOT), $post_id);
    //
    //
    //     /* General */
    //     $FirstName = $record->get('FirstName') ? trim($record->get('FirstName')) : '';
    //     $LastName = $record->get('LastName') ? trim($record->get('LastName')) : '';
    //     $DirectWorkPhone = $record->get('DirectWorkPhone') ? trim($record->get('DirectWorkPhone')) : '';
    //     $CellPhone = $record->get('CellPhone') ? trim($record->get('CellPhone')) : '';
    //     $WebPageAddress = $record->get('WebPageAddress') ? trim($record->get('WebPageAddress')) : '';
    //     $Email = $record->get('Email') ? trim($record->get('Email')) : '';
    //
    //     $MemberNumber = $record->get('MemberNumber') ? $record->get('MemberNumber') : false;
    //     $imageUrl = $MemberNumber ? "https://pics.harstatic.com/agent/" . $MemberNumber . ".jpg" : '';
    //
    //     $general_arr = array(
    //         'FirstName' => $FirstName,
    //         'LastName' => $LastName,
    //         'DirectWorkPhone' => $DirectWorkPhone,
    //         'CellPhone' => $CellPhone,
    //         'WebPageAddress' => $WebPageAddress,
    //         'Email' => $Email,
    //         'Image' => $imageUrl
    //     );
    //     update_field('general', json_encode($general_arr, JSON_HEX_QUOT), $post_id);
    //
    //
    //     /* Images */
    //     // if($record->get('PhotoCount') > 0) {
    //     //     $objects = $rets->GetObject('Agent', 'LargePhoto', $record->get('Matrix_Unique_ID'), '*', 1);
    //     //     $images = array();
    //     //     if( !empty($objects) ) {
    //     //         foreach ($objects as $index => $object) {
    //     //             if ($index < 1) continue;
    //     //             $images[] = $object->getLocation();
    //     //         }
    //     //     }
    //     //     update_field('images', json_encode($images), $post_id);
    //
    //     //     // $MemberNumber = $record->get('MemberNumber') ? $record->get('MemberNumber') : false;
    //     //     // if($MemberNumber) {
    //     //     //     $imageUrl = "https://pics.harstatic.com/agent/" . $MemberNumber . ".jpg";
    //     //     //     update_field('mls_image', $imageUrl, $post_id);
    //     //     // }
    //     // }
    //
    //     // Update post title and permalink (post_name)
    //     $title = $FirstName . ' ' . $LastName;
    //     $title = str_replace('  ', ' ', trim($title));
    //
    //     $post_name = $FirstName . '-' . $LastName;
    //     $post_name = str_replace('--', '-', trim($post_name, '-'));
    //
    //     $post_data = array(
    //         'ID' => $post_id,
    //         'post_title' => wp_strip_all_tags($title),
    //         'post_name' => $post_name
    //     );
    //     wp_update_post($post_data);
    // }
    //
    // function update_openhouse_fields($post_id, $record)
    // {
    //
    //     global $wpdb;
    //     $wp_ = $wpdb->prefix;
    //     $check = false;
    //     $openhouse_mls = array();
    //
    //     $Matrix_Unique_ID = $record->get('matrix_unique_id') ? trim($record->get('matrix_unique_id')) : '';
    //     $MatrixModifiedDT = $record->get('MatrixModifiedDT') ? trim($record->get('MatrixModifiedDT')) : '';
    //     $OpenHouseDate = $record->get('OpenHouseDate') ? trim($record->get('OpenHouseDate')) : '';
    //     $StartTime = $record->get('StartTime') ? trim($record->get('StartTime')) : '';
    //     $EndTime = $record->get('EndTime') ? trim($record->get('EndTime')) : '';
    //
    //     $new_openhouse_row = array(
    //         'Matrix_Unique_ID' => $Matrix_Unique_ID,
    //         'MLSupdate' => $MatrixModifiedDT,
    //         'OpenHouseDate' => $OpenHouseDate,
    //         'StartTime' => $StartTime,
    //         'EndTime' => $EndTime
    //     );
    //
    //     $Matrix_Unique_ID = $record->get('Matrix_Unique_ID');
    //     $query = $wpdb->prepare("SELECT openhouse_mls FROM {$wp_}reb_mls_openhouse WHERE post_id = '%d'", $post_id);
    //     $openhouse_mls_db = json_decode($wpdb->get_var($query), true);
    //
    //     if ($openhouse_mls_db) {
    //         $openhouse_mls = $openhouse_mls_db;
    //         $date = date('Y-m-d');
    //
    //         // Remove Old dates
    //         foreach ($openhouse_mls as $key => $row) {
    //             if ($row['OpenHouseDate'] < $date) {
    //                 unset($openhouse_mls[$key]);
    //             }
    //         }
    //
    //         // Find and replace current date row
    //         $int = 0;
    //         foreach ($openhouse_mls as $row) {
    //             if ($row['Matrix_Unique_ID'] == $new_openhouse_row['Matrix_Unique_ID']) {
    //                 Log::info('Update row - Old: ');
    //                 Log::info($openhouse_mls[$int]);
    //                 $openhouse_mls[$int] = $new_openhouse_row;
    //                 Log::info('Update row - New: ');
    //                 Log::info($openhouse_mls[$int]);
    //                 $check = true;
    //                 break;
    //             }
    //             $int++;
    //         }
    //     }
    //
    //     if (!$check || $openhouse_mls_db == NULL) {
    //         $openhouse_mls[] = $new_openhouse_row;
    //         Log::info('Add new row: ');
    //         Log::info($new_openhouse_row);
    //     }
    //
    //     usort($openhouse_mls, function ($a, $b) {
    //         if ($a["OpenHouseDate"] == $b["OpenHouseDate"]) {
    //             return 0;
    //         }
    //         return ($a["OpenHouseDate"] < $b["OpenHouseDate"]) ? -1 : 1;
    //     });
    //
    //     update_field('openhouse_mls', json_encode($openhouse_mls, JSON_HEX_QUOT), $post_id);
    //
    //     // Add clear old dates
    //
    // }
    //
    // // Attach Property locations
    // function update_property_index($post_id, $record, $class)
    // {
    //
    //     global $wpdb;
    //
    //     // State
    //     $State = $record->get('StateOrProvince') ? trim($record->get('StateOrProvince')) : '';
    //     $state = !empty($State) ? reb_get_states(array('state_abr' => $State)) : NULL;
    //     $fk_state = !empty($state) ? $state[0]->state_id : NULL;
    //
    //     // County
    //     $CountyOrParish = $record->get('CountyOrParish') ? trim($record->get('CountyOrParish')) : '';
    //     $county = !empty($CountyOrParish) ? reb_get_counties(array('county_%' => $CountyOrParish, 'state' => $fk_state)) : NULL;
    //     $fk_county = !empty($county) ? $county[0]->county_id : NULL;
    //
    //     // City
    //     $City = $record->get('City') ? trim($record->get('City')) : '';
    //     $city = !empty($City) ? reb_get_cities(array('city_name' => $City, 'state' => $fk_state)) : NULL;
    //     $fk_city = !empty($city) ? $city[0]->city_id : NULL;
    //
    //     // Neighborhood
    //     //$GeoMarketArea  = $record->get('MLSAreaMajor') ? trim( $record->get('MLSAreaMajor') ) : '';
    //     $fk_neighborhood = Null;
    //
    //     // Postal Code
    //     $PostalCode = $record->get('PostalCode') ? trim($record->get('PostalCode')) : '';
    //     $postal = !empty($PostalCode) ? reb_get_postal_codes(array('postal_code' => $PostalCode)) : NULL;
    //     $fk_postal = !empty($postal) ? $postal[0]->postal_id : NULL;
    //
    //     $check_postal = reb_get_postal_codes(array('state' => $fk_state, 'county' => $fk_county, 'city' => $fk_city, 'postal_code' => $PostalCode,));
    //
    //     if (!empty($check_postal)) {
    //         $mls_diff = 'ok';
    //     } else {
    //         $mls_diff = json_encode(array('state' => $State, 'county' => $CountyOrParish, 'city' => $City, 'postal_code' => $PostalCode), JSON_HEX_QUOT);
    //     }
    //
    //     $prop_type_id = NULL;
    //     // $PropertyType = $record->get('PropertySubType') ? trim( $record->get('PropertySubType') ) : '';
    //     $PropertyType = $record->get('PropertyType') ? trim($record->get('PropertyType')) : '';
    //     $PropertySubType = $record->get('PropertySubType') ? trim($record->get('PropertySubType')) : '';
    //     // $AssetClass = $record->get('AssetClass') ? trim( $record->get('AssetClass') ) : '';
    //     switch ($PropertySubType) {
    //         case 'Condominium':
    //         case 'Own Your Own':
    //         case 'Stock Cooperative':
    //             $prop_type_id = 4;
    //             break;
    //         case 'Apartment':
    //             $prop_type_id = 10;
    //             break;
    //         case 'Single Family Residence':
    //             $prop_type_id = 5;
    //             break;
    //         case 'Multi Family':
    //             $prop_type_id = 2;
    //             break;
    //         case 'Duplex':
    //             $prop_type_id = 8;
    //             break;
    //         case 'Triplex':
    //             $prop_type_id = 7;
    //             break;
    //         case 'Quadruplex':
    //             $prop_type_id = 9;
    //             break;
    //         case 'Townhouse':
    //             $prop_type_id = 6;
    //             break;
    //         case 'Water Position With':
    //         case 'Water Position Without Land':
    //         case 'Unimproved Land':
    //             // case 'Agriculture':
    //             // case 'Farm':
    //             // case 'Ranch':
    //             $prop_type_id = 3;
    //             break;
    //         case 'Manufactured Home':
    //         case 'Manufactured On Land':
    //             $prop_type_id = 3;
    //             break;
    //         case 'Mobile Home':
    //             $prop_type_id = 14;
    //             break;
    //         case 'Loft':
    //             $prop_type_id = 15;
    //             break;
    //         case 'Studio':
    //             $prop_type_id = 16;
    //             break;
    //         case 'Cabin':
    //             $prop_type_id = 17;
    //             break;
    //         default:
    //             switch ($class) {
    //                 case 'ManufacturedInPark':
    //                     $prop_type_id = 11;
    //                     break;
    //                 case 'Land':
    //                     $prop_type_id = 3;
    //                     break;
    //                 case 'CommercialSale':
    //                 case 'CommercialLease':
    //                     $prop_type_id = 1;
    //                     break;
    //                 default :
    //                     $prop_type_id = 12;
    //                     break;
    //             }
    //     }
    //
    //     $list_type = NULL;
    //     switch ($class) {
    //         case 'CommercialLease':
    //         case 'ResidentialLease':
    //             $list_type = 2;
    //             break;
    //         case 'CrossProperty':
    //             switch ($PropertyType) {
    //                 case 'Residential Lease':
    //                 case 'Commercial Lease':
    //                     $list_type = 2;
    //                     break;
    //                 default :
    //                     $list_type = 1;
    //                     break;
    //             }
    //             break;
    //         default :
    //             $list_type = 1;
    //             break;
    //     }
    //
    //     $status = NULL;
    //     $mls_prop_status = $record->get('StandardStatus');
    //     switch ($mls_prop_status) {
    //         case 'Active':
    //             $status = 1;
    //             break;
    //         case 'Active Under Contract':
    //             $status = 2;
    //             break;
    //         case 'Hold':
    //             $status = 4;
    //             break;
    //         case 'Pending':
    //             $status = 3;
    //             break;
    //         case 'Canceled':
    //             $status = 5;
    //             break;
    //         case 'Closed':
    //             $status = 6;
    //             break;
    //         case 'Withdrawn':
    //             $status = 7;
    //             break;
    //         case 'Expired':
    //             $status = 8;
    //             break;
    //         case 'Delete':
    //             $status = 9;
    //             break;
    //         case 'Incomplete':
    //             $status = 10;
    //             break;
    //         default :
    //             $status = NULL;
    //             break;
    //     }
    //
    //     $check_post = $wpdb->get_var("SELECT id FROM {$wpdb->prefix}reb_property_index WHERE post_id = $post_id");
    //     if (empty($check_post)) {
    //         $wpdb->insert(
    //             "{$wpdb->prefix}reb_property_index",
    //             array('post_id' => $post_id, 'fk_state' => $fk_state, 'fk_county' => $fk_county, 'fk_city' => $fk_city, 'fk_neighborhood' => $fk_neighborhood, 'fk_postal' => $fk_postal, 'mls_diff' => $mls_diff, 'fk_prop_type' => $prop_type_id, 'fk_list_type' => $list_type, 'fk_status' => $status),
    //             array('%d', '%d', '%d', '%d', '%d', '%d', '%s', '%d', '%d', '%d')
    //         );
    //     } else {
    //         $wpdb->update(
    //             "{$wpdb->prefix}reb_property_index",
    //             array('fk_state' => $fk_state, 'fk_county' => $fk_county, 'fk_city' => $fk_city, 'fk_neighborhood' => $fk_neighborhood, 'fk_postal' => $fk_postal, 'mls_diff' => $mls_diff, 'fk_prop_type' => $prop_type_id, 'fk_list_type' => $list_type, 'fk_status' => $status),
    //             array('post_id' => $post_id),
    //             array('%d', '%d', '%d', '%d', '%d', '%s', '%d', '%d', '%d'),
    //             array('%d')
    //         );
    //     }
    //     // $prop_loc = $wpdb->insert_id;
    //
    // }
    //
    // // Delete not active OpenHouse
    // function clear_openhouse()
    // {
    //     global $wpdb, $rets;
    //     $wp_ = $wpdb->prefix;
    //
    //     $buletin = $rets->login();
    //     if ($buletin) {
    //         Log::info('-------');
    //         Log::info('Start OpenHouse Clear');
    //         $date = strtotime(date('Y-m-d'));
    //         // write_log($date);
    //         $openhouse_mls = $wpdb->get_results("SELECT post_id, openhouse_mls FROM {$wp_}reb_mls_openhouse", ARRAY_A);
    //         if ($openhouse_mls) {
    //             foreach ($openhouse_mls as $row) {
    //
    //                 $db_record = json_decode($row['openhouse_mls'], true);
    //
    //                 foreach ($db_record as $key => $row_record) {
    //
    //                     // Duplicate from update_openhouse_fields
    //
    //                     // Remove Old dates
    //                     if (strtotime($row_record['OpenHouseDate']) < $date) {
    //                         Log::info('Unset olddate row : ');
    //                         Log::info($row_record);
    //                         unset($db_record[$key]);
    //                         continue;
    //                     }
    //
    //                     $query = '(matrix_unique_id=' . $row_record['Matrix_Unique_ID'] . ')';
    //                     $records = $rets->Search(
    //                         'OpenHouse',
    //                         'OpenHouse',
    //                         $query,
    //                         ['Format' => 'COMPACT-DECODED', 'Select' => 'ActiveYN', 'StandardNames' => 0, 'Limit' => 1, 'Offset' => 0]
    //                     );
    //
    //                     if ($records->getReturnedResultsCount() > 0) {
    //                         $ActiveYN = $records->first()->get('ActiveYN');
    //                         if (!$ActiveYN) {
    //                             Log::info('Unset notactive row : ');
    //                             Log::info($row_record);
    //                             unset($db_record[$key]);
    //                         }
    //                     } else {
    //                         Log::info('Unset notfound row : ');
    //                         Log::info($row_record);
    //                         unset($db_record[$key]);
    //                     }
    //
    //                 }
    //
    //                 if (!empty($db_record)) {
    //                     update_field('openhouse_mls', json_encode(array_values($db_record), JSON_HEX_QUOT), $row['post_id']);
    //                 } else {
    //                     $wpdb->delete($wp_ . 'reb_mls_openhouse', array('post_id' => $row['post_id']), array('%d'));
    //                     Log::info('delete row : ' . $row['post_id']);
    //                 }
    //             }
    //         }
    //         Log::info('End OpenHouse Clear');
    //     }
    // }
    //
    // function reb_property_action()
    // {
    //     if (!wp_next_scheduled('reb_property_hook')) {
    //         wp_schedule_event(time(), 'daily', 'reb_property_hook');
    //     }
    // }
    //
    // function reb_mls_logs($message)
    // {
    //     if (is_array($message)) {
    //         $message = json_encode($message);
    //     }
    //     $file = fopen(WP_CONTENT_DIR . "/reb_logs/reb_mls.log", "a");
    //     echo fwrite($file, "\n" . date('Y-m-d H:i:s') . " :: " . $message);
    //     fclose($file);
    // }
    //
    // function reb_check_prop_logs($message)
    // {
    //     if (is_array($message)) {
    //         $message = json_encode($message);
    //     }
    //     $file = fopen(WP_CONTENT_DIR . "/reb_logs/reb_check_prop_logs.log", "a");
    //     echo fwrite($file, "\n" . date('Y-m-d H:i:s') . " :: " . $message);
    //     fclose($file);
    // }
    //
    // function reb_props_change_types()
    // {
    //     global $wpdb;
    //     $wp_ = $wpdb->prefix;
    //
    //     $query = "SELECT post_id, JSON_UNQUOTE(JSON_EXTRACT(mls, \"$.MLSClass\")) as class, JSON_UNQUOTE(JSON_EXTRACT(mls, \"$.Status\")) as stat FROM {$wp_}reb_mls_listing WHERE JSON_VALID(mls)";
    //     $props = $wpdb->get_results($query);
    //
    //     $list_type = NULL;
    //     $status = NULL;
    //     if (!empty($props)) {
    //         foreach ($props as $prop) {
    //             switch ($prop->class) {
    //                 case 'CommercialLease':
    //                 case 'ResidentialLease':
    //                     $list_type = 2;
    //                     break;
    //                 default :
    //                     $list_type = 1;
    //                     break;
    //             }
    //             switch ($prop->stat) {
    //                 case 'Active':
    //                     $status = 1;
    //                     break;
    //                 case 'Unavailable':
    //                 case 'Closed':
    //                     $status = 0;
    //                 default :
    //                     $status = NULL;
    //                     break;
    //             }
    //             $check = $wpdb->update(
    //                 "{$wpdb->prefix}reb_property_index",
    //                 array('fk_list_type' => $list_type, 'status' => $status),
    //                 array('post_id' => $prop->post_id),
    //                 array('%d', '%d'),
    //                 array('%d')
    //             );
    //             if ($check) {
    //                 echo 'Success ' . $prop->post_id . ' ' . $list_type . '<br>';
    //             } else {
    //                 echo 'Bad ' . $prop->post_id . ' ' . $list_type . '<br>';
    //             }
    //         }
    //     }
    //
    //     return;
    // }
    //
    // function reb_cron_schedules($schedules)
    // {
    //     $schedules['two_min'] = array(
    //         'interval' => 60 * 2,
    //         'display' => 'Once at 2 minutes'
    //     );
    //     $schedules['one_min'] = array(
    //         'interval' => 60,
    //         'display' => 'Once at 1 minutes'
    //     );
    //     $schedules['half_min'] = array(
    //         'interval' => 30,
    //         'display' => 'Twise at 1 minutes'
    //     );
    //     $schedules['four_day'] = array(
    //         'interval' => 21600,
    //         'display' => 'Four times a day'
    //     );
    //     $schedules['eight_day'] = array(
    //         'interval' => 10800,
    //         'display' => 'Eight times a day'
    //     );
    //     return $schedules;
    // }
    //
    // function test_mls()
    // {
    //
    //     global $rets;
    //
    //     $PropertySubType = array(
    //         'RANC' => 'Ranch',
    //         'WPWL' => 'Water Position With Land',
    //         'WPOL' => 'Water Position Without Land',
    //         'BUSI' => 'Business',
    //         'MIXU' => 'Mixed Use',
    //         'WARH' => 'Warehouse',
    //         'ARGI' => 'Agriculture',
    //         'RMRT' => 'Rooms for Rent',
    //         'BSLP' => 'Boat Slip',
    //         'DDPK' => 'Deeded Parking',
    //         'DPLX' => 'Duplex',
    //         'QUAD' => 'Quadruplex',
    //         'TPLX' => 'Triplex',
    //         'FRM' => 'Farm',
    //         'TECH' => 'High Tech-Flex',
    //         'HOT' => 'Hotel/Motel',
    //         'IND' => 'Industrial',
    //         'LND' => 'Unimproved Land',
    //         'MOB' => 'Mobile Home',
    //         'MULT' => 'Multi Family',
    //         'OFF' => 'Office',
    //         'RET' => 'Retail',
    //         'SPEC' => 'Specialty',
    //         'MANH' => 'Manufactured Home',
    //         'LOFT' => 'Loft',
    //         'STUD' => 'Studio',
    //         'COMRES' => 'Commercial/Residential',
    //         'MANL' => 'Manufactured On Land',
    //         'CABIN' => 'Cabin',
    //         'CONDO' => 'Condominium',
    //         'COOP' => 'Stock Cooperative',
    //         'OYO' => 'Own Your Own',
    //         'SFR' => 'Single Family Residence',
    //         'TIME' => 'Timeshare',
    //         'TWNHS' => 'Townhouse',
    //         'APT' => 'Apartment'
    //     );
    //
    //     Log::info('test start');
    //
    //     $this->init_rets();
    //
    //     $buletin = $rets->login();
    //     if ($buletin) {
    //
    //         foreach ($PropertySubType as $key => $value) {
    //
    //             Log::info('$PropertySubType: ' . $value);
    //             echo '$PropertySubType: ' . $value;
    //
    //             $query = '(PropertySubType=' . $key . '),(StandardStatus=A,U),(StateOrProvince=CA)';//,(CountyOrParish=LA,OR,SD)';
    //             $records = $rets->Search('Property', 'ResidentialIncome', $query, ['Format' => 'COMPACT-DECODED', 'Select' => $this->mls_select, 'Limit' => 1]);
    //             // $ReturnedCount = intval($records->getReturnedResultsCount());
    //
    //             // Log::info('$ReturnedCount: '.$ReturnedCount);
    //             // echo '$ReturnedCount: '.$ReturnedCount;
    //
    //             $total_res = intval($records->getTotalResultsCount());
    //             Log::info('total_res: ' . $total_res);
    //
    //             // if( $ReturnedCount > 0 ) {
    //             //     $this->property_records_handler( $records, 'CrossProperty' );
    //             // }
    //         }
    //     }
    //
    //     Log::info('test end');
    //     return;
    // }
    //
    //
    // function rebuild_prop_action()
    // {
    //     if (!wp_next_scheduled('rebuild_prop_hook')) {
    //         wp_schedule_event(time(), 'daily', 'rebuild_prop_hook');
    //     }
    // }
    //
    // function rebuild_prop()
    // {
    //
    //     if (!get_option('reb_mls_flag')) {
    //
    //         update_option('reb_mls_flag', true, false);
    //
    //         global $rets, $wpdb;
    //         $wp_ = $wpdb->prefix;
    //         $class = 'CrossProperty';
    //
    //         $max_post_id = get_option('max_post_id') ? get_option('max_post_id') : 0;
    //
    //         if ($max_post_id === 0) {
    //             $timestamp = wp_next_scheduled('rebuild_prop_hook');
    //             wp_unschedule_event($timestamp, 'rebuild_prop_hook');
    //             wp_reschedule_event(time(), 'one_min', 'rebuild_prop_hook');
    //             Log::info('START rebuild_prop');
    //         } else {
    //             Log::info('CONTINUE rebuild_prop');
    //         }
    //
    //         $sql = "SELECT JSON_UNQUOTE(JSON_EXTRACT(mls, '$.Matrix_Unique_ID')) FROM {$wp_}reb_mls_listing WHERE JSON_VALID(mls) AND post_id > $max_post_id ORDER BY post_id ASC LIMIT 200";
    //         $muid_array = $wpdb->get_col($sql);
    //
    //         if (!empty($muid_array)) {
    //
    //             Log::info('$muid_array count: ' . count($muid_array));
    //
    //             $this->init_rets();
    //             $buletin = $rets->login();
    //             if ($buletin) {
    //
    //                 $muid_query = implode(',', $muid_array);
    //                 $query = '(ListingKeyNumeric=' . $muid_query . ')';
    //
    //                 $records = $rets->Search(
    //                     'Property',
    //                     $class,
    //                     $query,
    //                     ['Format' => 'COMPACT-DECODED', 'Select' => $this->mls_select, 'StandardNames' => 0]
    //                 );
    //
    //                 $ReturnedCount = intval($records->getReturnedResultsCount());
    //                 Log::info('$ReturnedCount: ' . $ReturnedCount);
    //
    //                 if ($ReturnedCount > 0) {
    //
    //                     $iterat = 1;
    //
    //                     foreach ($records as $record) {
    //
    //                         $Matrix_Unique_ID = $record->get('ListingKeyNumeric');
    //
    //                         $query = $wpdb->prepare("SELECT post_id FROM {$wp_}reb_mls_listing WHERE JSON_VALID(mls) AND JSON_UNQUOTE(JSON_EXTRACT(mls, \"$.Matrix_Unique_ID\")) = '%s'", $Matrix_Unique_ID);
    //                         $post_id = $wpdb->get_var($query);
    //                         if ($post_id !== NULL) {
    //
    //                             REB_mls::update_property_fields($post_id, $record, $class);
    //                             REB_mls::update_property_index($post_id, $record, $class);
    //                             Log::info('iterat: ' . $iterat . ', update_property_fields: ' . $post_id);
    //
    //                             if (($key = array_search($Matrix_Unique_ID, $muid_array)) !== false) {
    //                                 unset($muid_array[$key]);
    //                             }
    //
    //                             $max_post_id = $post_id > $max_post_id ? $post_id : $max_post_id;
    //
    //                         }
    //                         usleep(10000);
    //                         $iterat++;
    //                     }
    //
    //                     update_option('max_post_id', $max_post_id);
    //                 }
    //
    //                 if (!empty($muid_array)) {
    //                     REB_mls::reb_check_prop_logs(print_r($muid_array, true));
    //                     foreach ($muid_array as $muid) {
    //                         $query = $wpdb->prepare("SELECT post_id FROM {$wp_}reb_mls_listing WHERE JSON_VALID(mls) AND JSON_UNQUOTE(JSON_EXTRACT(mls, \"$.Matrix_Unique_ID\")) = '%s'", $muid);
    //                         $post_id = $wpdb->get_var($query);
    //                         if ($post_id !== NULL) {
    //                             $status = NULL;
    //                             $wpdb->update(
    //                                 "{$wpdb->prefix}reb_property_index",
    //                                 array('fk_status' => $status),
    //                                 array('post_id' => $post_id),
    //                                 array('%d'),
    //                                 array('%d')
    //                             );
    //                             REB_mls::reb_check_prop_logs('post_id: ' . $post_id);
    //                             REB_mls::reb_check_prop_logs('prop_link: ' . get_permalink($post_id));
    //                         }
    //                     }
    //                 }
    //             }
    //         } else {
    //             $timestamp = wp_next_scheduled('rebuild_prop_hook');
    //             wp_unschedule_event($timestamp, 'rebuild_prop_hook');
    //             wp_reschedule_event(time(), 'daily', 'rebuild_prop_hook');
    //             delete_option('max_post_id');
    //             Log::info('END rebuild_prop');
    //         }
    //
    //         update_option('reb_mls_flag', false, false);
    //     } else {
    //         Log::info("rebuild_prop() don't run flag is on");
    //     }
    // }


    /**
     * Format State
     *
     * Note: Does not format addresses, only states. $input should be as exact as possible, problems
     * will probably arise in long strings, example 'I live in Kentukcy' will produce Indiana.
     *
     * @example echo myClass::format_state( 'Florida', 'abbr'); // FL
     * @example echo myClass::format_state( 'we\'re from georgia' ) // Georgia
     *
     * @param string $input Input to be formatted
     * @param string $format Accepts 'abbr' to output abbreviated state, default full state name.
     * @return string          Formatted state on success,
     */
    static function format_state($input, $format = '')
    {
        if (!$input || empty($input))
            return;

        $states = array(
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming',
            'PR' => 'Puerto Rico',
        );

        foreach ($states as $abbr => $name) {
            if (preg_match("/\b($name)\b/", ucwords(strtolower($input)), $match)) {
                if ('abbr' == $format) {
                    return $abbr;
                } else return $name;
            } elseif (preg_match("/\b($abbr)\b/", strtoupper($input), $match)) {
                if ('abbr' == $format) {
                    return $abbr;
                } else return $name;
            }
        }
        return;
    }

}
