<div class="$WrapperClass">
  <% if $LogoShown %>
    <div class="logo"<% if $LogoTitle %> alt="$LogoTitle" title="$LogoTitle"<% end_if %>>
      <% if $PageLinkEnabled %><a href="$PageLink"><% end_if %>
      <% if $HasVectorLogo %>
        <img src="$LogoVector.URL" class="$VectorClass">
      <% end_if %>
      <% if $HasBitmapLogo %>
        <img src="$LogoBitmapResized.URL" class="$BitmapClass">
        <% if $UseRetinaBitmap %>
          <img src="$LogoBitmapRetina.URL" class="$RetinaClass">
        <% end_if %>
      <% end_if %>
      <% if $PageLinkEnabled %></a><% end_if %>
    </div>
  <% end_if %>
  <% if $IconShown %>
    <div class="icon"<% if $LogoTitle %> alt="$LogoTitle" title="$LogoTitle"<% end_if %>>
      <% if $PageLinkEnabled %><a href="$PageLink"><% end_if %>$FontIconTag<% if $PageLinkEnabled %></a><% end_if %>
    </div>
  <% end_if %>
  <% if $TextShown %>
    <div class="text">
      <% if $LogoTitle %><{$LogoTitleTag} class="title"><% if $PageLinkEnabled %><a href="$PageLink"><% end_if %><span>$LogoTitle</span><% if $PageLinkEnabled %></a><% end_if %></{$LogoTitleTag}><% end_if %>
      <% if $LogoSubtitle %><{$LogoSubtitleTag} class="subtitle"><% if $PageLinkEnabled %><a href="$PageLink"><% end_if %><span>$LogoSubtitle</span><% if $PageLinkEnabled %></a><% end_if %></{$LogoSubtitleTag}><% end_if %>
    </div>
  <% end_if %>
</div>