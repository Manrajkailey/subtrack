/**
 * Reads the site's chart colours from the CSS variables in style.css,
 * so the charts always match the site's colour palette.
 */
window.SubTrackColors = {
    read: function () {
        var s = getComputedStyle(document.documentElement);
        function v(name) { return s.getPropertyValue(name).trim(); }
        return {
            primary: v('--primary'),
            primarySoft: v('--chart-fill'),
            grid: v('--chart-grid'),
            tick: v('--chart-tick'),
            surface: v('--surface'),
            series: [v('--chart-1'), v('--chart-2'), v('--chart-3'), v('--chart-4'), v('--chart-5'), v('--chart-6')]
        };
    }
};
