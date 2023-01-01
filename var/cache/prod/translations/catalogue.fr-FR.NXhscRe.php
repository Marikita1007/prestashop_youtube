<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('fr-FR', array (
));

$catalogueFr = new MessageCatalogue('fr', array (
));
$catalogue->addFallbackCatalogue($catalogueFr);

return $catalogue;
