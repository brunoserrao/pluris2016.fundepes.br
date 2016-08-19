<?php
/**
 * Base controller for any admin screen related to a bundle
 */
abstract class Loco_admin_bundle_BaseController extends Loco_mvc_AdminController {

    /**
     * @var Loco_package_Bundle
     */
    private $bundle;

    /**
     * @var Loco_package_Project
     */
    private $project;


    /**
     * @return Loco_package_Bundle
     */
    public function getBundle(){
        if( ! isset($this->bundle) ){
            $type = $this->get('type');
            $handle = $this->get('bundle');
            $this->bundle = Loco_package_Bundle::createType( $type, $handle );
        }
        return $this->bundle; 
    }



    /**
     * Commit bundle config to database
     * @return Loco_admin_bundle_BaseController 
     */
    protected function saveBundle(){
        $custom = new Loco_config_CustomSaved;
        if( $custom->setBundle($this->bundle)->persist() ){
            Loco_error_AdminNotices::success( __('Configuration saved','loco') );
        }
        // invalidate bundle in memory so next fetch is re-configured from DB
        $this->bundle = null;
        return $this;
    }



    /**
     * Remove bundle config from database
     * @return Loco_admin_bundle_BaseController 
     */
    protected function resetBundle(){
        $option = $this->bundle->getCustomConfig();
        if( $option && $option->remove() ){
            Loco_error_AdminNotices::success( __('Configuration reset','loco') );
            // invalidate bundle in memory so next fetch falls back to auto-config
            $this->bundle = null;
        }
        return $this;
    }



    /**
     * @return Loco_package_Project
     */
    public function getProject(){
        if( ! $this->project ){
            $bundle = $this->getBundle();
            $domain = $this->get('domain');
            $this->project = $bundle->getProjectById($domain);
            if( ! $this->project ){
                throw new Loco_error_Exception( sprintf('Unknown translation project: %s not in %s', json_encode($domain), $bundle ) );
            }
        }

        return $this->project;
    }



    /**
     * @return Loco_admin_Navigation
     */
    protected function prepareNavigation(){
        $bundle = $this->getBundle();

        // navigate up to bundle listing page 
        $breadcrumb = Loco_admin_Navigation::createBreadcrumb( $bundle );
        $this->set( 'breadcrumb', $breadcrumb );
        
        // navigate between bundle view siblings
        $tabs = new Loco_admin_Navigation;
        $this->set( 'tabs', $tabs );
        $actions = array (
            'view'  => __('Overview','loco'),
            'setup' => __('Setup','loco'),
            'conf'  => __('Advanced','loco'),
        );
        if( loco_debugging() ){
            $actions['debug'] = __('Debug','loco');
        }
        $suffix = $this->get('action');
        $prefix = strtolower( $this->get('type') );
        $getarg = array_intersect_key( $_GET, array('bundle'=>'') );
        foreach( $actions as $action => $name ){
            $href = Loco_mvc_AdminRouter::generate( $prefix.'-'.$action, $getarg );
            $tabs->add( $name, $href, $action === $suffix );
        }
        
        return $breadcrumb;
    }



    /**
     * Prepare file system connect
     * @return Loco_mvc_HiddenFields
     */
    protected function prepareFsConnect( $type, $path ){
        $fields = new Loco_mvc_HiddenFields( array(
            'auth' => $type,
            'path' => $path,
            'loco-nonce' => wp_create_nonce('fsConnect'),
        ) );
        $this->set('fsFields', $fields );
        // may have credentials saved in pseudo session
        $session = Loco_data_Session::get();
        if( isset($session['loco-fs']) ){
            $fields['connection_type'] = $session['loco-fs']['connection_type'];
        }
        return $fields;
    }
    
}