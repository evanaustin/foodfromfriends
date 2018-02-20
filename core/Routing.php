<?php
 
class Routing extends Base {
    protected
        $DB;

    public
        $path,
        $landing,
        $fullpage,
        $unique,
        $template,
        $section,
        $subsection,
        $page;

    public
        $buyer_profiles,
        $seller_profiles,
        $profile_type,
        $buyer_type,
        $seller_type,
        $buyer,
        $seller;

    public
        $item_category,
        $item_subcategory;

    function __construct($parameters) {
        $this->DB = $parameters['DB'];

        $this->path = (empty($parameters['path']) ? $parameters['landing'] : $parameters['path']);
        $this->landing = $parameters['landing'];
        $this->fullpage = str_replace('/', '-', $this->path);

        $exp_path  = explode('/', $this->path);

        $this->unique = [
            'splash',
            'early-access-invitation',
            'team-member-invitation',
            'log-in'
        ];

        if (in_array($this->path, $this->unique)) {
            $this->template     = $this->path;
        } else if ($exp_path[0] == 'dashboard') {
            $this->template     = $exp_path[0];
            $this->section      = (isset($exp_path[1])) ? $exp_path[1] : null;
            $this->subsection   = (isset($exp_path[2])) ? $exp_path[2] : null;
            $this->page         = (isset($exp_path[3])) ? $exp_path[3] : null;
        }  else {
            $this->template     = 'front';
            $this->section      = $exp_path[0];

            // Manually set buyer types (just individuals for now)
            $this->buyer_profiles = [
                'user'
            ];

            // Retrieve & index seller operation types
            $seller_types = $this->retrieve([
                'table' => 'grower_operation_types'
            ]);

            foreach($seller_types as $type) {
                $this->seller_profiles[$type['id']] = $type['title'];
            }

            // Check if section is a buyer profile
            if (in_array($this->section, $this->buyer_profiles)) {
                $this->profile_type = 'buyer';
                $this->buyer_type   = $this->section;

                if (isset($exp_path[1])) {
                    $this->buyer    = $exp_path[1];
                    $this->path     = 'buyer-profile';
                }
            }

            // Check if section is a seller profile
            if (in_array($this->section, $this->seller_profiles)) {
                $this->profile_type = 'seller';
                $this->seller_type  = $this->section;

                // Check if seller slug is set
                if (isset($exp_path[1])) {
                    $this->seller = $exp_path[1];
                    
                    // Check if item category slug is set
                    if (isset($exp_path[2])) {
                        $Item = new FoodListing([
                            'DB' => $this->DB
                        ]);
                        
                        $Slug = new Slug([
                            'DB' => $this->DB
                        ]);
                                
                        // Get item category:subcateory associations & construct data structure to map relationships
                        $raw_assns      = $Item->get_category_associations();
                        $category_assns = [];

                        foreach ($raw_assns as $assn) {
                            $category_slug      = $Slug->slugify($assn['category_title']);
                            $subcategory_slug   = $Slug->slugify($assn['subcategory_title']);
    
                            $category_assns[$category_slug][$subcategory_slug] = $assn['subcategory_id'];
                        }
                        
                        // Check if item subcategory slug is set
                        if (isset($category_assns[$exp_path[2]])) {
                            $this->item_category = $exp_path[2];
                            
                            // Check if given subcategory actually belongs to given category
                            if (isset($exp_path[3], $category_assns[$exp_path[2]][$exp_path[3]])) {
                                // Item page
                                $this->path = 'item';

                                // (this is actually the subcategory ID)
                                $this->item_subcategory = $category_assns[$exp_path[2]][$exp_path[3]];
                            } else {
                                // Category:subcategory mis-association
                                // (direct to seller profile for now)
                                $this->path = 'seller-profile';
                            }
                        } else {
                            // Seller category page
                            $this->path = 'seller-profile';
                        }
                    } else {
                        // Seller profile
                        $this->path = 'seller-profile';
                    }
                } else {
                    // Seller type directory
                    // (seller type is set without a seller)
                }
            }
        }
    }
}

?>