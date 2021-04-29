<% with $FeaturedPage %>
  <article class="feature">
    <% if $Up.HasImage %>
      <% if $Up.ShowImage %>
        <div class="image">
          <% with $Up.ImageResized %>
            <img src="$URL" alt="$Title">
          <% end_with %>
        </div>
      <% end_if %>
    <% end_if %>
    <section class="content">
      <% if $Up.ShowPageTitle %>
        <header>
          <{$Up.HeadingTag}>
            <% if $Up.LinkTitle %>
              <a href="$MetaLink"><span>$MetaTitle</span></a>
            <% else %>
              <span>$MetaTitle</span>
            <% end_if %>
          </{$Up.HeadingTag}>
        </header>
      <% end_if %>
      <% if $Up.Summary %>
        <div class="summary">
          <p>$Up.Summary</p>
        </div>
      <% else_if $HasMetaSummary %>
        <div class="summary">
          <p>$MetaSummary</p>
        </div>
      <% end_if %>
      <% if $Up.ShowButton %>
        <footer>
          <% if $HasMetaLink %>
            <a href="$MetaLink" class="button"><span>$Up.ButtonLabel</span></a>
          <% end_if %>
        </footer>
      <% end_if %>
    </section>
  </article>
<% end_with %>