# Gene-Freq

## Inspiration

Nasa is intending to send 4-6 people to Mars in the next 20 years. There is significant evidence to suggest that space travel exposes astronauts to radiation, which may cause mutations to develop in their genomes. For this reason, it is imperative to have a method for detecting 'dangerous' mutations while on Mars. More specifically, this method must be able to be used by a non-genomics specialist, and have easily interpretable results. 

After coming up with Gene Freq we realized this type of technology has a wide range of applications. As genome sequencing costs get lower and lower, sequencing whole genomes will become more and more popular. Thus, this platform can be utilized by healthcare professionals to screen high risk candidates (civilians with a significant amount of exposure to carcinogens such as radiation) for possible cancer mutations.

## What it does

Ideally, the user would have a blood test before and after prolonged exposure to carcinogens. These blood samples would be sequenced, by a sequencing machine, and the outputs (fastq files) would be stored in a specified directory. 
After the user (i.e. the healthcare professional responsible for the given patient) signs in, they are able to specify the directory that holds the fastq files. Gene Freq then runs BWA mem (an open source software) to align reads to a reference genome. After, Gene Freq takes aligned sequences (Bam files) and inputs them into Strelka (another open source software) which detects genomic variants in the 'after' DNA. Gene Freq then uses data from the Cancer Gene Census to determine if these mutations are in cancer genes. If the mutations are in cancer genes, then Gene Freq uses a team-developed database of cancer probabilities to solve Bayes Theorem and calculate the probability that the patient has the cancer, given that they have the mutation. This information is displayed in an easily interpreted graph that can either display mutations as a function of quality score or probability of having the cancer. The graph also gives information about the gene that contains the mutation and the associated cancer.

## How we built it

The web application was built using javascripts. The application interfaces with a script written in R which runs BWA mem and Strelka, parses through data to find cancer hits, and calculates the estimated probability that the patient has the cancer given that they have the mutation.

## Challenges we ran into

We ran into several challenges surrounding the fact that genomic data is extremely large. This made testing our software very difficult (we didn't have a few days to let programs run). Therefore, we simulated the software using team-developed data. Thus, our application is merely a proof of concept and will need to be tested further in the future for optimization.

## Accomplishments that we're proud of

Normally, patients are diagnosed with cancer through sequencing of biopsies. However, this can be costly and has associated risks such as seeding new tumors. Our application can be used as a non-invasive method to preliminarily identify cancer risk by detecting mutations associated with circulating tumor cells in the blood. This type of development has far reach applications, and as the cost of sequencing continues to plummet, the need for bioinformatics software will only increase.  

## What we learned

We learned that it's hard to think on just a few hours of sleep! We also learned a lot about genomics in general, coding in R, and designing web applications.

## What's next for Gene Freq

Next up we will try to add more features to our app, such as adding specific patient and healthcare provider portals. We also want to test our software on actual genomics data, and possibly create our own variant calling algorithm with increased sensitivity. 
