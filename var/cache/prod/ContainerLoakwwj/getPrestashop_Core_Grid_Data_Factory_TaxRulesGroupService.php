<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'prestashop.core.grid.data.factory.tax_rules_group' shared service.

return $this->services['prestashop.core.grid.data.factory.tax_rules_group'] = new \PrestaShop\PrestaShop\Core\Grid\Data\Factory\DoctrineGridDataFactory(${($_ = isset($this->services['prestashop.core.grid.query_builder.tax_rules_group']) ? $this->services['prestashop.core.grid.query_builder.tax_rules_group'] : $this->load('getPrestashop_Core_Grid_QueryBuilder_TaxRulesGroupService.php')) && false ?: '_'}, ${($_ = isset($this->services['prestashop.core.hook.dispatcher']) ? $this->services['prestashop.core.hook.dispatcher'] : $this->getPrestashop_Core_Hook_DispatcherService()) && false ?: '_'}, ${($_ = isset($this->services['prestashop.core.grid.query.doctrine_query_parser']) ? $this->services['prestashop.core.grid.query.doctrine_query_parser'] : ($this->services['prestashop.core.grid.query.doctrine_query_parser'] = new \PrestaShop\PrestaShop\Core\Grid\Query\DoctrineQueryParser())) && false ?: '_'}, 'tax_rules_group');
