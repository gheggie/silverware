<li class="$ListItemClass">
  <a href="$MetaLink">
    <% if $HasMetaImage %>
      <div class="image">
        <% with $MetaImageResized($Component.ImageWidth, $Component.ImageHeight, $Component.ImageResize) %>
          <img src="$URL" alt="$Title">
        <% end_with %>
      </div>
    <% end_if %>
    <section class="content">
      <header>
        <h3><span>$MetaTitle</span></h3>
      </header>
      <% if $Component.ShowCaptions %>
        <% if $HasMetaImageCaption %>
          <div class="caption">
            <p>$MetaImageCaption</p>
          </div>
        <% end_if %>
      <% end_if %>
    </section>
  </a>
</li>