package org.ghns;

import org.apache.xerces.parsers.DOMParser;
import java.io.IOException;
import org.xml.sax.*;
import org.w3c.dom.*;

public class Helper
{
	public static Document xmldocument(String url)
	{
		DOMParser parser = new DOMParser();

		try
		{
			parser.setFeature("http://apache.org/xml/features/nonvalidating/load-external-dtd", false);
			System.out.println("- Parser configured :)");
		}
		catch(SAXNotSupportedException e)
		{
			System.err.println(e);
		}
		catch(SAXNotRecognizedException e)
		{
			System.err.println(e);
		}

		try
		{
			parser.parse(url);
			Document d = parser.getDocument();
		
			System.out.println("- Document loaded :)");

			return d;
		}
		catch(SAXException e)
		{
			System.err.println(e);
		}
		catch(IOException e)
		{
			System.err.println(e);
		}

		return null;
	}

	public static String xmlnodevalue(Node node)
	{
		NodeList nodes = node.getChildNodes();
		if(nodes.getLength() == 1)
		{
			Node textnode = nodes.item(0);
			String value = textnode.getNodeValue();
			return value;
		}
		return null;
	}
}

