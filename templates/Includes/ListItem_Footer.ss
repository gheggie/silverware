<% if $Component.ShowButtons %>
  <footer>
    <% if $HasMetaLink %>
      <a href="$MetaLink" class="button"><span>$Component.ButtonLabel</span></a>
    <% end_if %>
  </footer>
<% end_if %>