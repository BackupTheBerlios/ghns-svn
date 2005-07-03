package org.ghns;

import org.w3c.dom.*;
import org.ghns.Helper;

public class Entry
{
	String type = null;
	String name = null;
	String author = null;
	String licence = null;
	String summary = null;
	String version = null;
	String release = null;
	String releasedate = null;
	String preview = null;
	String payload = null;
	int rating = 0;
	int downloads = 0;

	public Entry(Node node)
	{
		Element stuffel = (Element)node;
		type = stuffel.getAttribute("type");

		NodeList properties = node.getChildNodes();
		for(int j = 0; j < properties.getLength(); j++)
		{
			Node property = properties.item(j);
			String tag = property.getNodeName();
			if(tag.equals("name"))
			{
				name = Helper.xmlnodevalue(property);
			}
			else if(tag.equals("author"))
			{
				author = Helper.xmlnodevalue(property);
			}
			else if(tag.equals("licence"))
			{
				licence = Helper.xmlnodevalue(property);
			}
			else if(tag.equals("summary"))
			{
				summary = Helper.xmlnodevalue(property);
			}
			else if(tag.equals("version"))
			{
				version = Helper.xmlnodevalue(property);
			}
			else if(tag.equals("release"))
			{
				release = Helper.xmlnodevalue(property);
			}
			else if(tag.equals("releasedate"))
			{
				releasedate = Helper.xmlnodevalue(property);
			}
			else if(tag.equals("preview"))
			{
				preview = Helper.xmlnodevalue(property);
			}
			else if(tag.equals("payload"))
			{
				payload = Helper.xmlnodevalue(property);
			}
			else if(tag.equals("rating"))
			{
				String ratingstr = Helper.xmlnodevalue(property);
				rating = new Integer(ratingstr).intValue();
			}
			else if(tag.equals("downloads"))
			{
				String downloadsstr = Helper.xmlnodevalue(property);
				downloads = new Integer(downloadsstr).intValue();
			}
		}
	}

	public String getType()
	{
		return type;
	}

	public String getName()
	{
		return name;
	}

	public String getAuthor()
	{
		return author;
	}

	public String getLicence()
	{
		return licence;
	}

	public String getSummary()
	{
		return summary;
	}

	public String getVersion()
	{
		return version;
	}

	public String getRelease()
	{
		return release;
	}

	public String getReleaseDate()
	{
		return releasedate;
	}

	public String getPreview()
	{
		return preview;
	}

	public String getPayload()
	{
		return payload;
	}

	public int getRating()
	{
		return rating;
	}

	public int getDownloads()
	{
		return downloads;
	}
}

