package org.ghns;

import org.w3c.dom.*;
import org.ghns.Helper;

public class Provider
{
	String downloadurl = null;
	String uploadurl = null;
	String icon = null;
	String webaccess = null;
	String title = null;

	public Provider(String url)
	{
		Document d = Helper.xmldocument(url);

		if(d != null)
		{
			System.out.println("- Document loaded :)");

			load(d);
		}
	}

	public String getTitle()
	{
		return title;
	}

	public String getWebAccess()
	{
		return webaccess;
	}

	public String getUploadUrl()
	{
		return uploadurl;
	}

	public String getDownloadUrl()
	{
		return downloadurl;
	}

	public String getIcon()
	{
		return icon;
	}

	private void load(Document d)
	{
		Element el = d.getDocumentElement();
		NodeList nodes = el.getElementsByTagName("provider");
		if(nodes.getLength() == 1)
		{
			Node provider = nodes.item(0);
			Element providerel = (Element)provider;
			downloadurl = providerel.getAttribute("downloadurl");
			uploadurl = providerel.getAttribute("uploadurl");
			icon = providerel.getAttribute("icon");
			webaccess = providerel.getAttribute("webaccess");

			System.out.println("Upload: " + uploadurl);
			System.out.println("Download: " + downloadurl);
			System.out.println("Icon: " + icon);
			System.out.println("Webaccess: " + webaccess);

			nodes = providerel.getElementsByTagName("title");
			if(nodes.getLength() == 1)
			{
				Node titletag = nodes.item(0);
				title = Helper.xmlnodevalue(titletag);
				System.out.println("Titel: " + title);	
			}
			else
			{
				// ???
			}
		}
		else
		{
			// ???
		}
	}
}

