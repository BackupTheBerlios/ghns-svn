CLASSPATH = /usr/share/kaffe/Klasses.jar:/usr/share/java/xercesImpl.jar
JARFILES = Helper Provider Entry Engine

all: ghnsjar testprog

#gcj:
#	gcj -c test.java --classpath /usr/share/java/xercesImpl.jar
#	gcj -o test --main=Test test.o /usr/share/java/xercesImpl.jar
#	gcj -c test.java --classpath /usr/share/java/xercesImpl.jar
#	gcj -o test test.o -l-org-w3c-dom

ghnsjar:
	mkdir -p org/ghns
	for jar in $(JARFILES); do \
		jikes $${jar}.java -classpath $(CLASSPATH):org/ghns; \
		mv $${jar}.class org/ghns; \
	done
	jar cvf ghns.jar org
	rm -rf org

testprog:
	jikes test.java DownloadDialog.java -classpath $(CLASSPATH):ghns.jar

test:
	kaffe -classpath .:$(CLASSPATH):ghns.jar Test

clean:
	rm -f *.jar
	rm -f *.class

