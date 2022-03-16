<?php

// Part of php simple framework (psf)

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// Copyright (c) Petr Bena <petr@bena.rocks> 2015 - 2018

namespace PSF;

if (!defined("PSF_ENTRY_POINT"))
    die("Not a valid psf entry point");

//! This widget provides a simple paging UI, you specify starting, ending and current page and this widget
//! turns into HTML code that will contain a list of page links, including some complex logic to shorten
class PagingWidget extends \HtmlContainer
{
    public $StartPage = 1;
    public $EndPage = 10;
    public $SelectedPage = 1;
    //! If there is more pages than this, widget will trim the list for easier navigation
    public $MaxPages = 20;
    //! In case pages are trimmed because there is too many, there will be N pages surrounding the current page, as well as first and last page
    public $PageGap = 4;
    public $PagingURLLink = "?page=";
    public $Prefix = "Page: ";
    
    public function __construct($end_page, $selected_page = 1)
    {
        $this->EndPage = $end_page;
        $this->SelectedPage = $selected_page;
    }

    public function ToHtml()
    {
        $result = $this->Prefix;
        $page_count = $this->EndPage - $this->StartPage;
        if ($page_count < 0)
            return "(invalid page count)";
        if ($page_count > $this->MaxPages)
        {
            // Add first page + N next pages
            $current_page = $this->StartPage;
            while ($current_page < $this->StartPage + $this->PageGap)
            {
                if ($current_page == $this->SelectedPage)
                {
                    $result .= "<b><a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a></b> ";
                } else
                {
                    $result .= "<a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a> ";
                }
                $current_page++;
            }

            // Add middle of list, this is tricky because there is many edge-case situations
            if ((($current_page - 1) - $this->SelectedPage) >= 0)
            {
                // Selected page is in beginning of the list
                // In case selected page is too close to page gap, add more pages after it (1 2 [3] 4 5 6), or (1 [2] 3 4)
                $additional_pages = $this->PageGap - (($current_page - 1) - $this->SelectedPage);
                while ($additional_pages > 0)
                {
                    if ($current_page == $this->SelectedPage)
                    {
                        $result .= "<b><a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a></b> ";
                    } else
                    {
                        $result .= "<a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a> ";
                    }
                    $additional_pages--;
                    $current_page++;
                }
            } else if ($this->SelectedPage > ($current_page + $this->PageGap))
            {
                // Selected page is in middle of list
                $result .= " ... ";
                // Add selected page with N surrounding pages
                $current_page = $this->SelectedPage - $this->PageGap;
                while ($current_page <= $this->SelectedPage + $this->PageGap && $current_page < $this->EndPage)
                {
                    if ($current_page == $this->SelectedPage)
                    {
                        $result .= "<b><a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a></b> ";
                    } else
                    {
                        $result .= "<a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a> ";
                    }
                    $current_page++;
                }
            } else
            {
                // Selected page is close to beginning of list
                $last = $this->SelectedPage + $this->PageGap + 1;
                while ($current_page < $last && $current_page < $this->EndPage)
                {
                    if ($current_page == $this->SelectedPage)
                    {
                        $result .= "<b><a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a></b> ";
                    } else
                    {
                        $result .= "<a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a> ";
                    }
                    $current_page++;
                }
            }

            // Add end of list
            if ($this->EndPage - $current_page <= $this->PageGap)
            {
                // Selected page is too close to end of list
                while ($current_page < $this->EndPage)
                {
                    if ($current_page == $this->SelectedPage)
                    {
                        $result .= "<b><a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a></b> ";
                    } else
                    {
                        $result .= "<a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a> ";
                    }
                    $current_page++;
                }
            } else
            {
                // Selected page is far from end of list
                $result .= " ... ";
                $current_page = $this->EndPage - $this->PageGap;
                while ($current_page < $this->EndPage)
                {
                    if ($current_page == $this->SelectedPage)
                    {
                        $result .= "<b><a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a></b> ";
                    } else
                    {
                        $result .= "<a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a> ";
                    }
                    $current_page++;
                }
            }
        } else
        {
            $current_page = $this->StartPage;
            while ($current_page < $this->EndPage)
            {
                if ($current_page == $this->SelectedPage)
                {
                    $result .= "<b><a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a></b> ";
                } else
                {
                    $result .= "<a href=\"" . $this->PagingURLLink . "${current_page}\">${current_page}</a> ";
                }
                $current_page++;
            }
        }
        return trim($result);
    }
}