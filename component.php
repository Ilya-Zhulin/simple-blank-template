<?php
// no direct access
use Joomla\CMS\Factory;

defined('_JEXEC') or die;

$app             = Factory::getApplication();
$doc             = $app->getDocument();
$this->language  = $doc->language;
$this->direction = $doc->direction;

$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/theme.css');
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<?php
if (file_exists(JPATH_THEMES . '/' . $this->template . '/includes/head.php'))
{
	include JPATH_THEMES . '/' . $this->template . '/includes/head.php';
}
?>

<jdoc:include type="head"/>

<body>
<jdoc:include type="message"/>
<jdoc:include type="component"/>
</body>

</html>
