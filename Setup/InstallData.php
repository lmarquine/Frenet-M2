<?php


namespace MagedIn\Frenet\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Eav\Setup\EavSetupFactory;

class InstallData implements InstallDataInterface
{

    private $eavSetupFactory;

    /**
     * Constructor
     *
     * @param \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
     */
    public function __construct(EavSetupFactory $eavSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $eavSetup,
        ModuleContextInterface $context
    ) {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $eavSetup]);


        // Add volume to product attribute set
        $codigo = 'volume_comprimento';
        $config = array(
            'position' => 1,
            'required' => 1,
            'label'    => 'Comprimento (cm)',
            'type'     => 'int',
            'input'    => 'text',
            'apply_to' => 'simple,bundle,grouped,configurable',
            'default'  => 16,
            'note'     => 'Comprimento da embalagem do produto (Para cálculo de frete, mínimo de 16cm)'
        );

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);

        // Add volume to product attribute set
        $codigo = 'volume_altura';
        $config = array(
            'position' => 1,
            'required' => 1,
            'label'    => 'Altura (cm)',
            'type'     => 'int',
            'input'    => 'text',
            'apply_to' => 'simple,bundle,grouped,configurable',
            'default'  => 2,
            'note'     => 'Altura da embalagem do produto (Para cálculo de frete, mínimo de 2cm)'
        );

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);

        // Add volume to product attribute set
        $codigo = 'volume_largura';
        $config = array(
            'position' => 1,
            'required' => 1,
            'label'    => 'Largura (cm)',
            'type'     => 'int',
            'input'    => 'text',
            'apply_to' => 'simple,bundle,grouped,configurable',
            'default'  => 11,
            'note'     => 'Largura da embalagem do produto (Para cálculo de frete, mínimo de 11cm)'
        );

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);

        // Add leadtime to product attribute set
        $codigo = 'leadtime';
        $config = array(
            'position' => 1,
            'required' => 1,
            'label'    => 'Lead time (dias)',
            'type'     => 'int',
            'input'    => 'text',
            'apply_to' => 'simple,bundle,grouped,configurable',
            'default'  => 0,
            'note'     => 'Tempo de fabricação do produto (Para cálculo de frete)'
        );

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);

        // Add fragile to product attribute set
        $codigo = 'fragile';
        $config = array(
            'position' => 1,
            'label'    => 'Produto frágil?',
            'type'     => 'int',
            'input'    => 'boolean',
            'apply_to' => 'simple,bundle,grouped,configurable',
            'default'  => 0,
            'required' => 1,
            'note'     => 'Produto contém vidro ou outros materiais frágeis? (Para cálculo de frete)'
        );

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, $codigo, $config);

    }
}