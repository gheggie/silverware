<% if $Component.ShowTitles %>
  <header>
    <{$Component.HeadingTag}>
      <% if $Component.LinkTitles && $HasMetaTitleLink($Component.TitleLinksTo) %>
        <a href="$MetaTitleLink($Component.TitleLinksTo)" rel="$Component.HTMLID" class="$Component.TitleLinkClass" title="$MetaTitle" data-caption-title="$MetaTitle" data-caption-desc="$MetaImageCaption"><span>$MetaTitle</span></a>
      <% else %>
        <span>$MetaTitle</span>
      <% end_if %>
    </{$Component.HeadingTag}>
  </header>
<% end_if %>