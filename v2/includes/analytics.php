<?php
// no direct access
defined('_JEXEC') or die;
?>

<?php if ($googleid): ?>
	<!-- Google Analytics -->
	<script>
		(function (b, o, i, l, e, r) {
			b.GoogleAnalyticsObject = l;
			b[l] || (b[l] =
					function () {
						(b[l].q = b[l].q || []).push(arguments)
					});
			b[l].l = +new Date;
			e = o.createElement(i);
			r = o.getElementsByTagName(i)[0];
			e.src = '//www.google-analytics.com/analytics.js';
			r.parentNode.insertBefore(e, r)
		}(window, document, 'script', 'ga'));
		ga('create', '<?php echo $googleid; ?>', 'auto');
		ga('send', 'pageview');
	</script>
	<!-- /Google Analytics -->
<?php endif; ?>

<?php if ($yandexid): ?>
	<!-- Yandex Metrika -->
	<script type="text/javascript" >
		(function (m, e, t, r, i, k, a) {
			m[i] = m[i] || function () {
				(m[i].a = m[i].a || []).push(arguments)
			};
			m[i].l = 1 * new Date();
			for (var j = 0; j < document.scripts.length; j++) {
				if (document.scripts[j].src === r) {
					return;
				}
			}
			k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
		})
				(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

		ym(<?php echo $yandexid; ?>, "init", {
			clickmap: true,
			trackLinks: true,
			accurateTrackBounce: true,
			webvisor: true
		});
	</script>
	<noscript><div><img src="https://mc.yandex.ru/watch/<?php echo $yandexid; ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
	<!-- /Yandex Metrika -->
<?php endif; ?>
