<?php

namespace MagedIn\Frenet\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetupFactory;

class InstallData implements InstallDataInterface
{

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /** @var EavSetupFactory */
    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     * @param \Psr\Log\LoggerInterface           $logger
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        try {
            /** @var EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            // new instalation
            if (!$context->getVersion() || version_compare($context->getVersion(), '1.0.0') == 0) {
                $this->configureNewInstallation($eavSetup);
            }

            //code to upgrade new version
            if (version_compare($context->getVersion(), '1.0.1') == 0) {
            }

            if (version_compare($context->getVersion(), '1.0.2') == 0) {
                //code to upgrade to 1.0.1
            }
        } catch (\Exception $ex) {
            $this->logger->critical("Frenet-InstallData-Error: " . $ex->getMessage());
        }

        $setup->endSetup();

        $this->logger->info("Frenet InstallData finalizado");
    }

    /**
     * Creates the new attributes during the module installation.
     *
     * @param EavSetup $eavSetup
     */
    protected function configureNewInstallation(EavSetup $eavSetup)
    {
        //code to upgrade to 1.0.0
        $code = 'volume_comprimento';
        $description = 'Comprimento da embalagem do produto (Para cálculo de frete, mínimo de 16cm)';
        $config = $this->prepareConfiguration('Comprimento (cm)', 16, $description);

        $this->configureProductAttribute($eavSetup, $code, $config);

        $code = 'volume_altura';
        $description = 'Altura da embalagem do produto (Para cálculo de frete, mínimo de 2cm)';
        $config = $this->prepareConfiguration('Altura (cm)', 2, $description);
        $this->configureProductAttribute($eavSetup, $code, $config);

        $code = 'volume_largura';
        $description = 'Largura da embalagem do produto (Para cálculo de frete, mínimo de 11cm)';
        $config = $this->prepareConfiguration('Largura (cm)', 11, $description);
        $this->configureProductAttribute($eavSetup, $code, $config);

        // Add leadtime to product attribute set
        $code = 'leadtime';
        $description = 'Tempo de fabricação do produto (Para cálculo de frete)';
        $config = $this->prepareConfiguration('Lead time (dias)', 0, $description);
        $this->configureProductAttribute($eavSetup, $code, $config);

        // Add fragile to product attribute set
        $code = 'fragile';
        $description = 'Produto contém vidro ou outros materiais frágeis? (Para cálculo de frete)';
        $config = $this->prepareConfiguration('Produto frágil?', 0, $description, 'boolean');
        $this->configureProductAttribute($eavSetup, $code, $config);
    }

    /**
     * Creates the config array with default data.
     *
     * @param string $label
     * @param mixed  $defaultValue
     * @param string $description
     * @param string $input
     *
     * @return array
     */
    protected function prepareConfiguration($label, $defaultValue, $description, $input = 'text')
    {
        if (!isset($input)) {
            $input = 'text';
        }

        $productTypes = implode(',', [
            \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
            \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL,
            \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE,
            \Magento\Catalog\Model\Product\Type::TYPE_BUNDLE,
        ]);

        return [
            'label'                   => $label,
            'default'                 => $defaultValue,
            'note'                    => $description,
            'input'                   => $input,
            'apply_to'                => $productTypes,
            'type'                    => 'int',
            'group'                   => 'Cotação de Frete',
            'backend'                 => \Magento\Catalog\Model\Product\Attribute\Backend\Price::class,
            'frontend'                => '',
            'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible'                 => true,
            'required'                => false,
            'user_defined'            => false,
            'input_renderer'          => \Magento\Msrp\Block\Adminhtml\Product\Helper\Form\Type::class,
            'frontend_input_renderer' => \Magento\Msrp\Block\Adminhtml\Product\Helper\Form\Type::class,
            'visible_on_front'        => true,
            'used_in_product_listing' => true,
            'is_used_in_grid'         => true,
            'is_visible_in_grid'      => false,
            'is_filterable_in_grid'   => true
        ];
    }

    /**
     * Configures the new attribute.
     *
     * @param EavSetup $eavSetup
     * @param string   $code
     * @param array    $config
     */
    protected function configureProductAttribute(EavSetup $eavSetup, $code, array $config)
    {
        try {
            $this->logger->debug('Frenet-configureProductAttribute: ' . $code);
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $code, $config);
        } catch (\Exception $ex) {
            $this->logger
                ->critical("Frenet-configureProductAttribute-Error: attr: " . $code . "; error:" . $ex->getMessage());
        }
    }
}
