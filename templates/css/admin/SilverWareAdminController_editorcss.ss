<% with $SiteConfig %>
  <% loop $SilverWareFonts %>
    @import url($URL);
  <% end_loop %>
  $ConfigExtensionCSSAsString
<% end_with %>

<% if $ExtraCSS %>
  $ExtraCSS
<% end_if %>

html {
  font-size: 62.5%;
}