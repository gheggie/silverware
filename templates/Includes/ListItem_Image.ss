<% if $HasMetaImage %>
  <% if $Component.ImageShown($IsFirst) %>
    <div class="image">
      <% if $Component.LinkImages && $HasMetaImageLink($Component.ImageLinksTo) %><a href="$MetaImageLink($Component.ImageLinksTo)" rel="{$Component.HTMLID}" title="$MetaTitle" class="$Component.ImageLinkClass" data-caption-title="$MetaTitle" data-caption-desc="$MetaImageCaption"><% end_if %>
      <% with $MetaImageResized($Component.ImageWidth, $Component.ImageHeight, $Component.ImageResize) %>
        <img src="$URL" alt="$Title">
      <% end_with %>
      <% if $Component.LinkImages && $HasMetaImageLink($Component.ImageLinksTo) %></a><% end_if %>
    </div>
  <% end_if %>
<% end_if %>