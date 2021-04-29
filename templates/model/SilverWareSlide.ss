<div class="$WrapperClass">
  <% if $LinkShown %><a href="$Link"<% if $OpenLinkInNewTab %> target="_blank"<% end_if %>><% end_if %>
  <% if $ImageShown %>
    <div class="image">
      <% with $ImageResized %>
        <img src="$URL" alt="$Title">
      <% end_with %>
    </div>
  <% end_if %>
  <% if $ContentShown %>
    <div class="content">
      <% if $TitleShown %>
        <header>
          <h3><span>$Title</span></h3>
        </header>
      <% end_if %>
      <% if $CaptionShown %>
        <div class="caption">
          <p>$Caption</p>
        </div>
      <% end_if %>
    </div>
  <% end_if %>
  <% if $LinkShown %></a><% end_if %>
</div>