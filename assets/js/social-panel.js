/**
 * Painel "Social Kit" no editor de blocos (Gutenberg).
 * Sem build step: usa wp.element.createElement diretamente.
 */
(function (wp) {
	'use strict';

	if (!wp || !wp.plugins || !wp.editPost) {
		return;
	}

	var el = wp.element.createElement;
	var useState = wp.element.useState;
	var registerPlugin = wp.plugins.registerPlugin;
	var PluginDocumentSettingPanel = wp.editPost.PluginDocumentSettingPanel;
	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;
	var TextControl = wp.components.TextControl;
	var TextareaControl = wp.components.TextareaControl;
	var CheckboxControl = wp.components.CheckboxControl;
	var Button = wp.components.Button;
	var __ = wp.i18n.__;

	var settings = window.urlshbymSocialKit || {};
	var limits = settings.limits || { label: 20, card_title: 30, card_text: 170, caption: 280, hashtags: 3 };
	var strings = settings.strings || {};

	var EMOJI_PATTERN = /[\u{1F300}-\u{1FAFF}\u{2600}-\u{27BF}]/gu;
	var URL_PATTERN = /https?:\/\/\S+/g;

	function countWeighted(text) {
		if (!text) {
			return 0;
		}

		var urlMatches = text.match(URL_PATTERN) || [];
		var withoutUrls = text.replace(URL_PATTERN, '');
		var emojiMatches = withoutUrls.match(EMOJI_PATTERN) || [];

		return withoutUrls.length + urlMatches.length * 23 + emojiMatches.length;
	}

	function counterColor(count, limit) {
		if (count > limit) {
			return '#d63638';
		}
		if (count >= limit * 0.9) {
			return '#dba617';
		}
		return '#666';
	}

	function copyToClipboard(text) {
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(text);
			return;
		}

		var textarea = document.createElement('textarea');
		textarea.value = text;
		textarea.style.position = 'fixed';
		textarea.style.opacity = '0';
		document.body.appendChild(textarea);
		textarea.select();
		try {
			document.execCommand('copy');
		} catch (e) {
			// Sem suporte a cópia neste navegador: ignora silenciosamente.
		}
		document.body.removeChild(textarea);
	}

	function Field(props) {
		var count = countWeighted(props.value || '');
		var color = counterColor(count, props.limit);
		var Control = props.multiline ? TextareaControl : TextControl;
		var copiedState = useState(false);
		var copied = copiedState[0];
		var setCopied = copiedState[1];

		return el(
			'div',
			{ className: 'urlshbym-social-field' },
			el(Control, {
				label: props.label,
				value: props.value || '',
				onChange: props.onChange,
			}),
			el(
				'div',
				{ className: 'urlshbym-social-field-footer' },
				el(
					'span',
					{ style: { color: color } },
					count + '/' + props.limit
				),
				el(
					Button,
					{
						variant: 'secondary',
						isSmall: true,
						onClick: function () {
							copyToClipboard(props.value || '');
							setCopied(true);
							setTimeout(function () {
								setCopied(false);
							}, 2000);
						},
					},
					copied ? strings.copied || 'Copied!' : strings.copy || 'Copy'
				)
			)
		);
	}

	function SocialKitPanel() {
		var meta = useSelect(function (select) {
			return select('core/editor').getEditedPostAttribute('meta') || {};
		}, []);

		var editPost = useDispatch('core/editor').editPost;
		var savePost = useDispatch('core/editor').savePost;

		var label = meta._urlshbym_social_label || '';
		var cardTitle = meta._urlshbym_social_card_title || '';
		var cardText = meta._urlshbym_social_card_text || '';
		var captionX = meta._urlshbym_social_caption_x || '';
		var locked = !!meta._urlshbym_social_locked;

		function updateField(key, value) {
			var next = {};
			next[key] = value;
			next._urlshbym_social_locked = true;
			editPost({ meta: next });
		}

		function regenerate() {
			editPost({
				meta: {
					_urlshbym_social_locked: false,
					_urlshbym_social_source_hash: '',
				},
			});
			savePost();
		}

		function copyAll() {
			var parts = [label, cardTitle, cardText, captionX].filter(Boolean);
			copyToClipboard(parts.join('\n\n'));
		}

		function openOnX() {
			window.open('https://x.com/intent/post?text=' + encodeURIComponent(captionX), '_blank');
		}

		return el(
			PluginDocumentSettingPanel,
			{ name: 'urlshbym-social-kit-panel', title: strings.panelTitle || 'Social Kit' },
			!captionX && !cardTitle
				? el('p', {}, strings.noShortUrlYet || 'Publish the post to generate a short URL first.')
				: null,
			el(Field, { label: strings.label || 'Label', value: label, limit: limits.label, onChange: function (v) { updateField('_urlshbym_social_label', v); } }),
			el(Field, { label: strings.cardTitle || 'Card title', value: cardTitle, limit: limits.card_title, onChange: function (v) { updateField('_urlshbym_social_card_title', v); } }),
			el(Field, { label: strings.cardText || 'Card text', value: cardText, limit: limits.card_text, multiline: true, onChange: function (v) { updateField('_urlshbym_social_card_text', v); } }),
			el(Field, { label: strings.captionX || 'X caption', value: captionX, limit: limits.caption, multiline: true, onChange: function (v) { updateField('_urlshbym_social_caption_x', v); } }),
			el(CheckboxControl, {
				label: strings.locked || 'Lock editing',
				checked: locked,
				onChange: function (value) {
					editPost({ meta: { _urlshbym_social_locked: value } });
				},
			}),
			el(
				'div',
				{ className: 'urlshbym-social-actions' },
				el(Button, { variant: 'secondary', onClick: regenerate }, strings.regenerate || 'Regenerate'),
				el(Button, { variant: 'secondary', onClick: copyAll }, strings.copyAll || 'Copy all'),
				el(Button, { variant: 'secondary', onClick: openOnX, disabled: !captionX }, strings.openOnX || 'Open on X')
			)
		);
	}

	registerPlugin('urlshbym-social-kit', {
		render: SocialKitPanel,
	});
})(window.wp);
