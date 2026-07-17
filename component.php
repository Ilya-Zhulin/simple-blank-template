<?php
// no direct access
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$app             = Factory::getApplication();
$doc             = $app::getDocument();
$this->language  = $doc->language;
$this->direction = $doc->direction;

$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/theme.css');
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<?php include 'includes/head.php'; ?>

<body>
<jdoc:include type="message"/>
<jdoc:include type="component"/>
</body>

</html>
