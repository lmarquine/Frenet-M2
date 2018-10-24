<?php


namespace MagedIn\Frenet\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetupFactory;

class InstallData implements InstallDataInterface
{

    protected $logger;

    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param \Psr\Log\LoggerInterface $logger  
     */
    public function __construct(EavSetupFactory $eavSetupFactory, \Psr\Log\LoggerInterface $logger  )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {

        $this->logger->info("Frenet InstallData iniciado");

        $setup->startSetup();

        try {
            /** @var EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            // new instalation
            if(!$context->getVersion() || version_compare($context->getVersion(), '1.0.0') == 0) {
                $this->configureNewInstalation($eavSetup);
            }
     
            //code to upgrade new version
            if (version_compare($context->getVersion(), '1.0.1') == 0) {
                
            }
     
            if (version_compare($context->getVersion(), '1.0.2') == 0) {
                //code to upgrade to 1.0.1
            }

        }
        catch (\Exception $ex) {
            $this->logger->critical("Frenet-InstallData-Error: ". $ex->getMessage() );
        }

        $setup->endSetup();

        $this->logger->info("Frenet InstallData finalizado");
    }

    /**
     * Criar os novos atributos durante a primeira instalação
     */
    protected function configureNewInstalation($eavSetup) {
        //code to upgrade to 1.0.0
        $codigo = 'volume_comprimento';
        $config = $this->prepareConfiguration('Comprimento (cm)', 16, 'Comprimento da embalagem do produto (Para cálculo de frete, mínimo de 16cm)');
        $this->configureProductAttribute($eavSetup, $codigo, $config);

        $codigo = 'volume_altura';
        $config = $this->prepareConfiguration('Altura (cm)', 2, 'Altura da embalagem do produto (Para cálculo de frete, mínimo de 2cm)');                
        $this->configureProductAttribute($eavSetup, $codigo, $config);
        
        $codigo = 'volume_largura';
        $config = $this->prepareConfiguration('Largura (cm)', 11, 'Largura da embalagem do produto (Para cálculo de frete, mínimo de 11cm)');
        $this->configureProductAttribute($eavSetup, $codigo, $config);

        // Add leadtime to product attribute set
        $codigo = 'leadtime';
        $config = $this->prepareConfiguration('Lead time (dias)', 0, 'Tempo de fabricação do produto (Para cálculo de frete)');
        $this->configureProductAttribute($eavSetup, $codigo, $config);

        // Add fragile to product attribute set
        $codigo = 'fragile';
        $config = $this->prepareConfiguration('Produto frágil?', 0, 'Produto contém vidro ou outros materiais frágeis? (Para cálculo de frete)', 'boolean');
        $this->configureProductAttribute($eavSetup, $codigo, $config);
    }

    /**
     * Montar o array de configuração com os dados padrão 
     */
    protected function prepareConfiguration($label, $valueDefault, $description, $input = 'text') {

        if (!isset($input)) {
            $input = 'text';
        }

        $productTypes = [
            \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
            \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
            \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE,
            \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE,
        ];
        $productTypes = join(',', $productTypes);

        $config = [                        
            'label'    => $label,
            'default'  => $valueDefault,
            'note'     => $description,
            'input'    => $input,
            'apply_to' => $productTypes,
            
            'type'     => 'int',
            'group' => 'Cotação de frete',
            'backend' => \Magento\Catalog\Model\Product\Attribute\Backend\Price::class,
            'frontend' => '',            
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'input_renderer' => \Magento\Msrp\Block\Adminhtml\Product\Helper\Form\Type::class,
            'frontend_input_renderer' => \Magento\Msrp\Block\Adminhtml\Product\Helper\Form\Type::class,
            'visible_on_front' => true,
            'used_in_product_listing' => true,
            'is_used_in_grid' => true,
            'is_visible_in_grid' => false,
            'is_filterable_in_grid' => true
        ];

        return $config;
    }

    /**
     * Configurar o novo attribute
     */
    protected function configureProductAttribute($eavSetup, $codigo, $config) {
        try {
            $this->logger->debug('Frenet-configureProductAttribute: ' . $codigo);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);
        }
        catch (\Exception $ex) {
            $this->logger->critical("Frenet-configureProductAttribute-Error: attr: ". $codigo . "; error:" . $ex->getMessage() );
        }
    }

}