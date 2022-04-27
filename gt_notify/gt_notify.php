<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Gt_notify extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'gt_notify';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'Yanis Calvez';
        $this->need_instance = 0;

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l("Gt2i - vrai Module d'alerte email");
        $this->description = $this->l("Module de l'entreprise Gt2i, gérant l'envoi de mail après diverses actions.");

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }


    public function install()
    {
        Configuration::updateValue('GT_NOTIFY_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('actionUpdateQuantity');
    }

    public function uninstall()
    {
        Configuration::deleteByName('GT_NOTIFY_LIVE_MODE');

        return parent::uninstall();
    }


    public function getContent()
    {

        if (((bool)Tools::isSubmit('submitGt_notifyModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }


    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitGt_notifyModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }


    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Alertes '),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(

                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Les emails seront envoyés à cette adresse.'),
                        'name' => 'GT_NOTIFY_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),

                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }


    protected function getConfigFormValues()
    {
        return array(
            'GT_NOTIFY_ACCOUNT_EMAIL' => Configuration::get('GT_NOTIFY_ACCOUNT_EMAIL', 'contact@prestashop.com'),
        );
    }


    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();
        $destination_mail = (string) Tools::getValue('GT_NOTIFY_ACCOUNT_EMAIL');
        

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }


    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    

    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookActionUpdateQuantity($parameters)
    {
        $id_product = $parameters['id_product'];
        $id_product_attribute = $parameters['id_product_attribute'];
        $quantity = $parameters['quantity'];

        //envoi du mail
        $product_name = Product::getProductName($id_product, $id_product_attribute);
        $template_vars = [
            '{qty}' => $quantity,
            '{product}' => $product_name,
        ];
        
        Mail::Send(
            (int)(Configuration::get('PS_LANG_DEFAULT')),
            'contact',
            "Quantité d'un produit modifiée",
            $template_vars,
            Configuration::get('GT_NOTIFY_ACCOUNT_EMAIL'),          
            null,
            null,
            null,
            null,
            null,
            _PS_MODULE_DIR_ . 'gt_notify/mails'
        );
    }
}
