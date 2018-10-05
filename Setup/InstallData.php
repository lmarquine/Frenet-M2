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

            $productTypes = [
                \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
                \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE,
                \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE,
            ];
            $productTypes = join(',', $productTypes);

            if(!$context->getVersion()) {
                // new instalation
                // Add volume to product attribute set
                $codigo = 'volume_comprimento';
                $config = [
                    'group' => 'Cotação de frete',
                    'backend' => \Magento\Catalog\Model\Product\Attribute\Backend\Price::class,
                    'frontend' => '',
                    'label'    => 'Comprimento (cm)',
                    'type'     => 'int',
                    'input'    => 'text',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'apply_to' => $productTypes,
                    'input_renderer' => \Magento\Msrp\Block\Adminhtml\Product\Helper\Form\Type::class,
                    'frontend_input_renderer' => \Magento\Msrp\Block\Adminhtml\Product\Helper\Form\Type::class,
                    'default'  => 16,

                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'is_used_in_grid' => true,
                    'is_visible_in_grid' => true,
                    'is_filterable_in_grid' => true
                ];

                //     'default'  => 16,
                //     'note'     => 'Comprimento da embalagem do produto (Para cálculo de frete, mínimo de 16cm)',            
                //     'sort_order' => 50,
                    
                //     'is_used_in_grid' => true,
                //     'is_visible_in_grid' => true,
                //     'is_filterable_in_grid' => true,
                    
                //     'is_html_allowed_on_front' => false,
                //     'visible_on_front' => true
                // ];

                $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);

                // // Add volume to product attribute set
                // $codigo = 'volume_altura';
                // $config = [
                //     'position' => 1,
                //     'required' => 1,
                //     'label'    => 'Altura (cm)',
                //     'type'     => 'int',
                //     'input'    => 'text',
                //     'apply_to' => 'simple,bundle,grouped,configurable',
                //     'default'  => 2,
                //     'note'     => 'Altura da embalagem do produto (Para cálculo de frete, mínimo de 2cm)',
                //     'group' => 'General',
                //     'sort_order' => 50,
                //     'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                //     'is_used_in_grid' => true,
                //     'is_visible_in_grid' => true,
                //     'is_filterable_in_grid' => true,
                //     'visible' => true,
                //     'is_html_allowed_on_front' => true,
                //     'visible_on_front' => false
                // ];

                // $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);

                // // Add volume to product attribute set
                // $codigo = 'volume_largura';
                // $config = [
                //     'position' => 1,
                //     'required' => 1,
                //     'label'    => 'Largura (cm)',
                //     'type'     => 'int',
                //     'input'    => 'text',
                //     'apply_to' => 'simple,bundle,grouped,configurable',
                //     'default'  => 11,
                //     'note'     => 'Largura da embalagem do produto (Para cálculo de frete, mínimo de 11cm)',
                //     'group' => 'General',
                //     'sort_order' => 50,
                //     'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                //     'is_used_in_grid' => true,
                //     'is_visible_in_grid' => true,
                //     'is_filterable_in_grid' => true,
                //     'visible' => true,
                //     'is_html_allowed_on_front' => false,
                //     'visible_on_front' => true
                // ];

                // $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);

                // // Add leadtime to product attribute set
                // $codigo = 'leadtime';
                // $config = [
                //     'position' => 1,
                //     'required' => 1,
                //     'label'    => 'Lead time (dias)',
                //     'type'     => 'int',
                //     'input'    => 'text',
                //     'apply_to' => 'simple,bundle,grouped,configurable',
                //     'default'  => 0,
                //     'note'     => 'Tempo de fabricação do produto (Para cálculo de frete)',
                //     'group' => 'General',
                //     'sort_order' => 50,
                //     'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                //     'is_used_in_grid' => true,
                //     'is_visible_in_grid' => true,
                //     'is_filterable_in_grid' => true,
                //     'visible' => true,
                //     'is_html_allowed_on_front' => false,
                //     'visible_on_front' => true
                // ];

                // $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);

                // // Add fragile to product attribute set
                // $codigo = 'fragile';
                // $config = [
                //     'position' => 1,
                //     'label'    => 'Produto frágil?',
                //     'type'     => 'int',
                //     'input'    => 'boolean',
                //     'apply_to' => 'simple,bundle,grouped,configurable',
                //     'default'  => 0,
                //     'required' => 1,
                //     'note'     => 'Produto contém vidro ou outros materiais frágeis? (Para cálculo de frete)',
                //     'group' => 'General',
                //     'sort_order' => 50,
                //     'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                //     'is_used_in_grid' => true,
                //     'is_visible_in_grid' => true,
                //     'is_filterable_in_grid' => true,
                //     'visible' => true,
                //     'is_html_allowed_on_front' => false,
                //     'visible_on_front' => true
                // ];

                // $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);                        
            }
     
            if (version_compare($context->getVersion(), '1.0.1') < 0) {
                //code to upgrade to 1.0.1
            }
     
            if (version_compare($context->getVersion(), '1.0.3') < 0) {
                //code to upgrade to 1.0.3
            }

        }
        catch (\Exception $ex) {
            $this->logger->critical("Frenet-InstallData-Error: ". $ex->getMessage() );
        }

        $setup->endSetup();

        $this->logger->info("Frenet InstallData finalizado");
    }
}