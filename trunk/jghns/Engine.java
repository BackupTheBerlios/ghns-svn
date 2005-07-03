package org.ghns;

import java.util.ArrayList;
import org.w3c.dom.*;
import org.ghns.Helper;
import org.ghns.Provider;
import org.ghns.Entry;

public class Engine
{
	public Engine()
	{
	}

	public ArrayList getEntries(String url)
	{
		Provider p = new Provider(url);

		if(p.getDownloadUrl() != null)
		{
			ArrayList l = download(p.getDownloadUrl());
			return l;
		}
		return null;
	}

	private ArrayList download(String url)
	{
		Document d = Helper.xmldocument(url);
		ArrayList l = new ArrayList();

		System.out.println(">> Download!");

		if(d != null)
		{
			System.out.println("- Document loaded :)");

			Element el = d.getDocumentElement();
			NodeList nodes = el.getElementsByTagName("stuff");
			for(int i = 0; i < nodes.getLength(); i++)
			{
				Node stuff = nodes.item(i);
				Entry entry = new Entry(stuff);
				l.add(entry);
			}
		}

		return l;
	}
}

