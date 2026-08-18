(function () {
    'use strict';

    var panel = null;

    var SECTIONS = [
        { name: 'TOP-A', prefix: 'top-a_' },
        { name: 'TOP-B', prefix: 'top-b_' },
        { name: 'TOP-C', prefix: 'top-c_' },
        { name: 'MAIN-TOP', prefix: 'main-top_' },
        { name: 'MAIN CONTENT', prefix: 'main_' },
        { name: 'MAIN-BOTTOM', prefix: 'main-bottom_' },
        { name: 'MAIN SIDEBAR A', prefix: 'main-sidebar-a_' },
        { name: 'MAIN SIDEBAR B', prefix: 'main-sidebar-b_' },
        { name: 'BOTTOM-A', prefix: 'bottom-a_' },
        { name: 'BOTTOM-B', prefix: 'bottom-b_' },
        { name: 'BOTTOM-C', prefix: 'bottom-c_' },
        { name: 'SIDEBAR A', prefix: 'sidebar-a_' },
        { name: 'SIDEBAR B', prefix: 'sidebar-b_' },
        { name: 'OFFCANVAS A', prefix: 'off-canvas-a_' },
        { name: 'OFFCANVAS B', prefix: 'off-canvas-b_' }
    ];

    var tabBtn = null;
    var sections = null;

    function findField(namePrefix) {
        var els = document.querySelectorAll('input, select, textarea');
        for (var i = 0; i < els.length; i++) {
            var name = els[i].getAttribute('name') || '';
            if (name.indexOf('[' + namePrefix) !== -1) return els[i];
        }
        return null;
    }

    function findGroup(el) {
        while (el) {
            if (el.classList && el.classList.contains('control-group')) return el;
            el = el.parentElement;
        }
        return null;
    }

    function isSpacer(el) {
        return el && (el.classList.contains('field-spacer') || el.querySelector('hr'));
    }

    function findSpacerBefore(group) {
        var prev = group && group.previousElementSibling;
        if (!prev) return null;
        if (isSpacer(prev)) return prev;
        var prev2 = prev.previousElementSibling;
        if (prev2 && isSpacer(prev2)) return prev2;
        return null;
    }

    function collectSections() {
        var result = [];
        for (var i = 0; i < SECTIONS.length; i++) {
            var field = findField(SECTIONS[i].prefix);
            if (!field) continue;
            var group = findGroup(field) || field.parentElement;
            var spacer = findSpacerBefore(group);
            if (spacer) {
                var spacer2 = spacer.previousElementSibling;
                group = (spacer2 && isSpacer(spacer2)) ? spacer2 : spacer;
            }
            result.push({ name: SECTIONS[i].name, element: group });
        }
        return result;
    }

    function isTabActive() {
        return tabBtn && tabBtn.getAttribute('aria-selected') === 'true';
    }

    function showPanel() {
        if (!panel) return;
        panel.style.display = isTabActive() ? '' : 'none';
    }

    function onNavClick() {
        var idx = parseInt(this.getAttribute('data-index'), 10);
        if (!sections[idx]) return;
        var el = sections[idx].element;
        var rect = el.getBoundingClientRect();
        window.scrollBy({ top: rect.top - 80, behavior: 'smooth' });
        el.style.transition = 'box-shadow 0.2s';
        el.style.boxShadow = '0 0 0 3px #337ab7';
        setTimeout(function () { el.style.boxShadow = ''; }, 1500);
    }

    function build() {
        sections = collectSections();
        if (sections.length === 0) return;

        tabBtn = document.querySelector('[aria-controls="attrib-sections"]');
        if (!tabBtn) return;

        panel = document.createElement('div');
        panel.id = 'sb-section-nav';
        document.body.appendChild(panel);

        var html = '<div class="sb-nav-title">\u0421\u0435\u043a\u0446\u0438\u0438 (' + sections.length + ')</div>';
        for (var i = 0; i < sections.length; i++) {
            html += '<button type="button" class="sb-nav-btn" data-index="' + i + '">'
                + '<span class="sb-nav-btn-name">' + sections[i].name.replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</span>'
                + '</button>';
        }
        panel.innerHTML = html;

        var buttons = panel.querySelectorAll('.sb-nav-btn');
        for (var j = 0; j < buttons.length; j++) {
            buttons[j].addEventListener('click', onNavClick);
        }

        if (!document.getElementById('sb-section-nav-css')) {
            var s = document.createElement('style');
            s.id = 'sb-section-nav-css';
            s.textContent =
                '#sb-section-nav{position:fixed;right:20px;top:80px;z-index:9999;background:#fff;border:1px solid #ddd;border-radius:6px;box-shadow:0 2px 12px rgba(0,0,0,.15);max-height:calc(100vh - 120px);overflow-y:auto;min-width:160px;padding:8px 0;font-size:12px;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}'
                + '.sb-nav-title{padding:4px 12px 8px;font-weight:600;font-size:11px;text-transform:uppercase;color:#666;border-bottom:1px solid #eee;margin-bottom:4px}'
                + '.sb-nav-btn{display:flex;align-items:center;gap:6px;width:100%;border:none;background:none;padding:6px 12px;cursor:pointer;text-align:left;transition:background .15s}'
                + '.sb-nav-btn:hover{background:#f0f5ff}'
                + '.sb-nav-btn-name{font-weight:500;color:#333;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:100px}'
                + '.sb-nav-btn-section{font-size:10px;color:#999;background:#f5f5f5;border-radius:3px;padding:1px 5px;white-space:nowrap}';
            document.head.appendChild(s);
        }

        showPanel();

        var obs = new MutationObserver(showPanel);
        obs.observe(tabBtn, { attributes: true, attributeFilter: ['aria-selected'] });
    }

    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', build);
        } else {
            build();
        }
    }

    init();
})();
