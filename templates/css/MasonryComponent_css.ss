{$CSSID} .masonry-grid .masonry-grid-item {
  margin-bottom: {$Gutter}px;
}

{$CSSID} .masonry-grid .masonry-grid-item,
{$CSSID} .masonry-grid .masonry-grid-sizer {
  width: {$ColumnWidthNarrow}%;
}

<% if $DetectDeviceWidth %>

@media (min-width: 750px) {
  {$CSSID} .masonry-grid .masonry-grid-item,
  {$CSSID} .masonry-grid .masonry-grid-sizer {
    width: {$ColumnWidthWide}%;
  }
}

<% else %>

{$CSSID} .masonry-grid.wide .masonry-grid-item,
{$CSSID} .masonry-grid.wide .masonry-grid-sizer {
  width: {$ColumnWidthWide}%;
}

<% end_if %>