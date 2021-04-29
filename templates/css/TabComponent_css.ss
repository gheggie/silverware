{$CSSID} .r-tabs {
  border-width: {$BorderWidth}px;
<% if $BorderColor %>  border-color: #{$BorderColor};<% end_if %>
<% if $BorderStyle %>  border-style: {$BorderStyle};<% end_if %>
<% if $BackgroundColor %>  background-color: #{$BackgroundColor};<% end_if %>
}

<% if $TabsBackgroundColor %>
{$CSSID} .r-tabs .r-tabs-nav {
  background-color: #{$TabsBackgroundColor};
}
<% end_if %>

<% if $TabMarginRight %>
{$CSSID} .r-tabs .r-tabs-nav .r-tabs-tab {
  margin-right: {$TabMarginRight}px;
}
<% end_if %>

<% if $PanelBackgroundColor %>
{$CSSID} .r-tabs .r-tabs-panel {
  background-color: #{$PanelBackgroundColor};
}
<% end_if %>

{$CSSID} .r-tabs .r-tabs-nav .r-tabs-anchor,
{$CSSID} .r-tabs .r-tabs-accordion-title .r-tabs-anchor
{
<% if $DefaultTabForegroundColor %>  color: #{$DefaultTabForegroundColor};<% end_if %>
<% if $DefaultTabBackgroundColor %>  background-color: #{$DefaultTabBackgroundColor};<% end_if %>
}

{$CSSID} .r-tabs .r-tabs-nav .r-tabs-state-active .r-tabs-anchor,
{$CSSID} .r-tabs .r-tabs-accordion-title.r-tabs-state-active .r-tabs-anchor
{
<% if $ActiveTabForegroundColor %>    color: #{$ActiveTabForegroundColor};<% end_if %>
<% if $ActiveTabBackgroundColor %>    background-color: #{$ActiveTabBackgroundColor};<% end_if %>
}