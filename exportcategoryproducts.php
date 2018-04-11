<?php
if (!defined('_PS_VERSION_'))
{
  exit;
}

class ExportCategoryProducts extends Module
{
  public function __construct()
  {
    $this->name = 'exportcategoryproducts';
    $this->tab = 'front_office_features';
    $this->version = '1.0.0';
    $this->author = 'Willows Consulting';
    $this->need_instance = 0;
    $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    $this->bootstrap = true;

    parent::__construct();

    $this->displayName = $this->l('Export Category Products');
    $this->description = $this->l('This plugin allows you to export products from a category level.');

    $this->confirmUninstall = $this->l("'Are you sure you want to uninstall? :'(");

      if (!Configuration::get('EXPORTCATEGORYPRODUCTS_NAME'))
          $this->warning = $this->l('No name provided');

  }

  function hookDisplayLeftColumn($params)
  {
      
      if(Tools::getValue('controller') == "category"){
        $category = new Category( Tools::getValue('id_category'), 1);
        global $cookie;
        $id_lang = $cookie->id_lang;
        $array_of_params = array('category_id' => Tools::getValue('id_category'),'category_name' => $category->name, "language_id" => $id_lang);
        //$languages = Language::getLanguages(true);
        return '<a target="_blank" href="' . $this->context->link->getModuleLink('exportcategoryproducts','export',$array_of_params) .'" style="margin-top: 10px; margin-bottom: 10px;" class="btn btn-dark">Export Products</a>';        
      }
      
  }

    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        if (!parent::install() ||
            !$this->registerHook('leftColumn') ||
            !$this->registerHook('header') ||
            !Configuration::updateValue('EXPORTCATEGORYPRODUCTS_NAME', 'PDF Module')
        )
            return false;

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !Configuration::deleteByName('EXPORTCATEGORYPRODUCTS_NAME')
        )
            return false;

        return true;
    }

  
}