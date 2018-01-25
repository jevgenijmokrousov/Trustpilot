<?php

class Trustpilot_Reviews_Block_Header extends Mage_Core_Block_Template
{
   
    protected $_script_url;

    public function __construct()
    {
        $this->_script_url = 'https://invitejs.trustpilot.com/tp.min.js';
        parent::__construct();
    }
    
    public function renderScript()
    {
        $key = trim(Mage::getStoreConfig('trustpilot/trustpilot_general_group/trustpilot_key', Mage::app()->getStore()));
        return '
        <script type="text/javascript">
            (function(w,d,s,r,n){w.TrustpilotObject=n;w[n]=w[n]||function(){(w[n].q=w[n].q||[]).push(arguments)};
            a=d.createElement(s);a.async=1;a.src=r;a.type=\'text/java\'+s;f=d.getElementsByTagName(s)[0];
            f.parentNode.insertBefore(a,f)})(window,document,\'script\', \''.$this->_script_url.'\', \'tp\');
            tp(\'register\',\''.$key.'\');
        </script>';
    }
}
