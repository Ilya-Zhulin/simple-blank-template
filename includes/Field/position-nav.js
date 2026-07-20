/**
 * Position Navigation Panel
 * Quick-scroll buttons for custom positions subform
 */
(function () {
    'use strict';

    var panel = null;
    var subformEl = null;
    var contentObserver = null;
    var updateTimer = null;

    var SECTIONS = {
        'sb-top-a': 'Top-A', 'sb-top-b': 'Top-B', 'sb-top-c': 'Top-C',
        'sb-main-top': 'Main-Top', 'sb-main-sidebar-a': 'Main-SB-A',
        'sb-main-sidebar-b': 'Main-SB-B', 'sb-main-below': 'Main-Below',
        'sb-bottom-a': 'Bottom-A', 'sb-bottom-b': 'Bottom-B', 'sb-bottom-c': 'Bottom-C',
        'sb-off-canvas-a': 'Off-A', 'sb-off-canvas-b': 'Off-B',
        'sb-sidebar-a': 'Sidebar-A', 'sb-sidebar-b': 'Sidebar-B'
    };

    function findSubform() {
        return document.querySelector('joomla-field-subform[name*="positions-location"]');
    }

    function collectPositions() {
        var positions = [];
        if (!subformEl) return positions;
        var groups = subformEl.querySelectorAll('.subform-repeatable-group');
        for (var i = 0; i < groups.length; i++) {
            var nameInput = groups[i].querySelector('input[name*="[pos-name]"]');
            var sectionSelect = groups[i].querySelector('select[name*="[pos-section]"]');
            if (nameInput && sectionSelect) {
                var name = nameInput.value.trim();
                if (name) {
                    positions.push({ name: name, section: sectionSelect.value, element: groups[i] });
                }
            }
        }
        return positions;
    }

    function updatePanel() {
        var positions = collectPositions();
        if (positions.length === 0) {
            if (panel) panel.style.display = 'none';
            return;
        }
        if (!panel) createPanel();
        panel.style.display = '';

        var html = '<div class="sb-nav-title">\u041f\u043e\u0437\u0438\u0446\u0438\u0438 (' + positions.length + ')</div>';
        for (var i = 0; i < positions.length; i++) {
            var sec = SECTIONS[positions[i].section] || positions[i].section;
            html += '<button type="button" class="sb-nav-btn" data-index="' + i + '">'
                + '<span class="sb-nav-btn-name">' + positions[i].name.replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</span>'
                + '<span class="sb-nav-btn-section">' + sec + '</span>'
                + '</button>';
        }
        panel.innerHTML = html;

        var buttons = panel.querySelectorAll('.sb-nav-btn');
        for (var j = 0; j < buttons.length; j++) {
            buttons[j].addEventListener('click', onNavClick);
        }
    }

    function onNavClick() {
        var idx = parseInt(this.getAttribute('data-index'), 10);
        var allGroups = subformEl.querySelectorAll('.subform-repeatable-group');
        if (allGroups[idx]) {
            var rect = allGroups[idx].getBoundingClientRect();
            window.scrollBy({ top: rect.top - 60, behavior: 'smooth' });
            allGroups[idx].style.transition = 'box-shadow 0.2s';
            allGroups[idx].style.boxShadow = '0 0 0 3px #337ab7';
            (function (el) {
                setTimeout(function () { el.style.boxShadow = ''; }, 1500);
            })(allGroups[idx]);
        }
    }

    function createPanel() {
        panel = document.createElement('div');
        panel.id = 'sb-position-nav';
        document.body.appendChild(panel);
        injectStyles();
    }

    function injectStyles() {
        if (document.getElementById('sb-position-nav-css')) return;
        var s = document.createElement('style');
        s.id = 'sb-position-nav-css';
        s.textContent =
            '#sb-position-nav{position:fixed;right:20px;top:80px;z-index:9999;background:#fff;border:1px solid #ddd;border-radius:6px;box-shadow:0 2px 12px rgba(0,0,0,.15);max-height:calc(100vh - 120px);overflow-y:auto;min-width:160px;padding:8px 0;font-size:12px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}'
            + '.sb-nav-title{padding:4px 12px 8px;font-weight:600;font-size:11px;text-transform:uppercase;color:#666;border-bottom:1px solid #eee;margin-bottom:4px}'
            + '.sb-nav-btn{display:flex;align-items:center;gap:6px;width:100%;border:none;background:none;padding:6px 12px;cursor:pointer;text-align:left;transition:background .15s}'
            + '.sb-nav-btn:hover{background:#f0f5ff}'
            + '.sb-nav-btn-name{font-weight:500;color:#333;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100px}'
            + '.sb-nav-btn-section{font-size:10px;color:#999;background:#f5f5f5;border-radius:3px;padding:1px 5px;white-space:nowrap}';
        document.head.appendChild(s);
    }

    function scheduleUpdate() {
        if (updateTimer) clearTimeout(updateTimer);
        updateTimer = setTimeout(updatePanel, 200);
    }

    function startWatching() {
        if (contentObserver) contentObserver.disconnect();
        if (!subformEl) return;
        contentObserver = new MutationObserver(scheduleUpdate);
        contentObserver.observe(subformEl, { childList: true, subtree: true, characterData: true });
    }

    function setup() {
        updatePanel();
        startWatching();
        new IntersectionObserver(function (entries) {
            if (panel) panel.style.display = entries[0].isIntersecting ? '' : 'none';
        }).observe(subformEl);
    }

    function init() {
        subformEl = findSubform();
        if (subformEl) { setup(); return; }
        var tries = 0;
        var check = function () {
            subformEl = findSubform();
            if (subformEl) { setup(); return; }
            if (++tries <= 20) requestAnimationFrame(check);
        };
        requestAnimationFrame(check);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
