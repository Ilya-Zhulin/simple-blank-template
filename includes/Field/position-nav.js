/**
 * Position Navigation Panel
 * Quick-scroll buttons for custom positions subform
 * Vanilla JS — no jQuery
 */
(function () {
    'use strict';

    var PANEL_ID = 'sb-position-nav';
    var panel = null;
    var subformEl = null;
    var observer = null;
    var updateTimer = null;

    function findSubform() {
        return document.querySelector(
            'joomla-field-subform[name*="positions-location"]'
        );
    }

    function sectionLabel(val) {
        var map = {
            'sb-top-a': 'Top-A',
            'sb-top-b': 'Top-B',
            'sb-top-c': 'Top-C',
            'sb-main-top': 'Main-Top',
            'sb-main-sidebar-a': 'Main-SB-A',
            'sb-main-sidebar-b': 'Main-SB-B',
            'sb-main-below': 'Main-Below',
            'sb-bottom-a': 'Bottom-A',
            'sb-bottom-b': 'Bottom-B',
            'sb-bottom-c': 'Bottom-C',
            'sb-off-canvas-a': 'Off-A',
            'sb-off-canvas-b': 'Off-B',
            'sb-sidebar-a': 'Sidebar-A',
            'sb-sidebar-b': 'Sidebar-B'
        };
        return map[val] || val;
    }

    function collectPositions() {
        var positions = [];
        if (!subformEl) return positions;

        var groups = subformEl.querySelectorAll('.subform-repeatable-group');
        for (var i = 0; i < groups.length; i++) {
            var group = groups[i];
            var nameInput = group.querySelector('input[name*="[pos-name]"]');
            var sectionSelect = group.querySelector('select[name*="[pos-section]"]');
            if (nameInput && sectionSelect) {
                var name = nameInput.value.trim();
                var section = sectionSelect.value;
                if (name) {
                    positions.push({
                        name: name,
                        section: section,
                        element: group
                    });
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
            var pos = positions[i];
            var sec = sectionLabel(pos.section);
            html += '<button type="button" class="sb-nav-btn" data-index="' + i + '" '
                + 'title="' + escapeAttr(pos.name) + ' (' + sec + ')">'
                + '<span class="sb-nav-btn-name">' + escapeHtml(pos.name) + '</span>'
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
            window.scrollBy({top: rect.top - 80, behavior: 'smooth'});
            highlightGroup(allGroups[idx]);
        }
    }

    function highlightGroup(el) {
        el.style.transition = 'box-shadow 0.2s';
        el.style.boxShadow = '0 0 0 3px #337ab7';
        setTimeout(function () {
            el.style.boxShadow = '';
        }, 1500);
    }

    function createPanel() {
        panel = document.createElement('div');
        panel.id = PANEL_ID;
        document.body.appendChild(panel);
        injectStyles();
    }

    function escapeHtml(str) {
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    function escapeAttr(str) {
        return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function injectStyles() {
        if (document.getElementById('sb-position-nav-css')) return;
        var s = document.createElement('style');
        s.id = 'sb-position-nav-css';
        s.textContent =
            '#' + PANEL_ID + '{'
            + 'position:fixed;right:20px;top:80px;z-index:9999;'
            + 'background:#fff;border:1px solid #ddd;border-radius:6px;'
            + 'box-shadow:0 2px 12px rgba(0,0,0,.15);'
            + 'max-height:calc(100vh - 120px);overflow-y:auto;'
            + 'min-width:160px;padding:8px 0;font-size:12px;'
            + 'font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}'
            + '.sb-nav-title{padding:4px 12px 8px;font-weight:600;font-size:11px;'
            + 'text-transform:uppercase;color:#666;border-bottom:1px solid #eee;margin-bottom:4px}'
            + '.sb-nav-btn{display:flex;align-items:center;gap:6px;width:100%;'
            + 'border:none;background:none;padding:6px 12px;cursor:pointer;'
            + 'text-align:left;transition:background .15s}'
            + '.sb-nav-btn:hover{background:#f0f5ff}'
            + '.sb-nav-btn-name{font-weight:500;color:#333;white-space:nowrap;'
            + 'overflow:hidden;text-overflow:ellipsis;max-width:100px}'
            + '.sb-nav-btn-section{font-size:10px;color:#999;background:#f5f5f5;'
            + 'border-radius:3px;padding:1px 5px;white-space:nowrap}';
        document.head.appendChild(s);
    }

    function scheduleUpdate() {
        if (updateTimer) clearTimeout(updateTimer);
        updateTimer = setTimeout(updatePanel, 200);
    }

    function startWatching() {
        if (observer) observer.disconnect();
        if (!subformEl) return;

        observer = new MutationObserver(scheduleUpdate);
        observer.observe(subformEl, {
            childList: true,
            subtree: true,
            characterData: true
        });
    }

    function init() {
        subformEl = findSubform();
        if (!subformEl) return;
        updatePanel();
        startWatching();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(init, 500);
        });
    } else {
        setTimeout(init, 500);
    }
})();
